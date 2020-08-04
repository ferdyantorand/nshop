<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 18/10/2017
 * Time: 13:52
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class MidtransController extends Controller
{
    public function notification(){
        try
        {
            $json_result = file_get_contents('php://input');
            $json = json_decode($json_result);

            $orderid = $json->order_id;
//            sleep(15);

            $dateTimeNow = Carbon::now('Asia/Jakarta');
            $orderDB = Order::where('order_number', $orderid)->first();
            if($json->status_code == "200"){
                if(($json->transaction_status == "capture" || $json->transaction_status =="accept") && $json->fraud_status == "accept"){
                    $orderDB->order_status_id = 3;
                    $orderDB->save();

                    $user = User::find($orderDB->user_id);
                    Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Status Code = '. $json->status_code .', Transaction Status = '. $json->transaction_status . ', Fraud Status = '.$json->fraud_status);
                    Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Payment Credit Card success by '.$user->email.', order status '.$orderDB->order_status->name. ' (from Notification)');

                    return Response::json("success", 200);
                }
            }
            else if($json->status_code == "202"){
                $orderDB->order_status_id = 6;
                $orderDB->save();
                Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Status Code = '. $json->status_code .', Transaction Status = '. $json->transaction_status . ', Fraud Status = '.$json->fraud_status);
                Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Payment Credit Card failed (from Notification)');
                return Response::json("status code 202", 500);
            }
            else{
                // Log error exception here
                Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Status Code = '. $json->status_code .', Transaction Status = '. $json->transaction_status . ', Fraud Status = '.$json->fraud_status);
                Log::error("MidtransController > notification Error: status code either 200 or 202, order ID = ".$orderid. ' (from Notification)');
                return Response::json("status code either 200 or 202", 500);
            }
//            DB::transaction(function() use ($orderid, $json){
//
//            }, 5);
        }
        catch (\Exception $ex){
            Log::error("MidtransController > notification Error: ". $ex);
            return Response::json($ex." test", 500);
        }
    }
}
