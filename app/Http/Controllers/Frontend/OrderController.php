<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\NewTransferBank;
use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderBankTransfer;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function Index(){
        if (Auth::check())
        {
            //Read DB
            $user = Auth::user();
            $orders = Order::where('user_id', $user->id)->get();

            return view('frontend.transactions.orders', compact('orders'));
        }
        return Redirect::route('home');
        //dd($carts);
    }

    public function Show(Order $order){
        if (Auth::check())
        {
            $orderProduct = OrderProduct::where('order_id', $order->id)->get();

            return view('frontend.transactions.order', compact('order', 'orderProduct'));
        }
        return Redirect::route('home');
        //dd($carts);
    }

    public function ConfirmBankTransfer(Order $order){
        if (Auth::check())
        {
            $orderProduct = OrderProduct::where('order_id', $order->id)->get();

            return view('frontend.transactions.transfer_confirmation', compact('order', 'orderProduct'));
        }
        return Redirect::route('home');
        //dd($carts);
    }

    public function SubmitBankTransfer(Request $request){
        try{
            if (Auth::check())
            {
                $orderDB = Order::where('order_number', $request->input('order_number'))->first();
                $user = $orderDB->user;
                $dateTimeNow = Carbon::now('Asia/Jakarta');

                //checking if user already submit transfer bank
                $newOrderBankTransferExist = OrderBankTransfer::where('user_id', $orderDB->user_id)
                    ->where('order_id', $orderDB->id)
                    ->first();
                if(!empty($newOrderBankTransferExist)){
                    return Redirect::route('orders');
                }

                //create order transfer bank database
                $newOrderBankTransfer = OrderBankTransfer::create([
                    'user_id' => $orderDB->user_id,
                    'order_id' => $orderDB->id,
                    'bank_acc_no' => $request->input('bank_acc_no'),
                    'bank_acc_name' => $request->input('bank_acc_name'),
                    'bank_name' => $request->input('bank_name'),
                    'amount' => $request->input('amount'),
                    'status' => 0,
                    'created_at'        => $dateTimeNow->toDateTimeString(),
                ]);
                $orderDB->order_status_id = 8;
                $orderDB->save();

                //send email to admin
                $newTransferBank = new NewTransferBank($user, $orderDB);
                Mail::to(env('MAIL_SALES'))
                    ->send($newTransferBank);
                return Redirect::route('orders');
            }
            return Redirect::route('home');
            //dd($carts);
        }
        catch (\Exception $exception){
//            dd($exception);
            Log::error("OrderController/SubmitBankTransfer error: ". $exception);
            return Redirect::route('home');
        }
    }

}
