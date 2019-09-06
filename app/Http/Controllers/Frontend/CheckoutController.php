<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\libs\Midtrans;
use App\libs\Zoho;
use App\Mail\NewTransaction;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function getCheckout(Order $order)
    {
        if (Auth::check()){
            $user = Auth::user();
        }
        else{
            $user = Session::get('user');
        }
        if($order->user_id == $user->id){
            $orderProduct = OrderProduct::where('order_id', $order->id)->get();

            $isIndonesian = true;

            $isDevelopment = env('MIDTRANS_IS_DEVELOPMENT');

            if($isDevelopment == "true"){
                $snapURL = env('MIDTRANS_SNAP_SANDBOX');
                $clientKey = env('MIDTRANS_CLIENT_KEY_SANDBOX');
            }
            else{
                $snapURL = env('MIDTRANS_SNAP_PRODUCTION');
                $clientKey = env('MIDTRANS_CLIENT_KEY');
            }
            $data=([
                'order' => $order,
                'orderProduct' => $orderProduct,
                'isIndonesian' => $isIndonesian,
                'snapURL' => $snapURL,
                'clientKey' => $clientKey
            ]);

            //change order status to pending payment
            $orderDB = Order::find($order->id);
            $orderDB->order_status_id = 2;
            $orderDB->save();

            return view('frontend.transactions.checkout')->with($data);
        }
        return redirect()->route('home');
    }

    public function submitCheckout(Request $request){
        // Credit card = credit_card
        // Bank Transfer = bank_transfer, echannel,
        // Internet Banking = bca_klikpay, bca_klikbca, mandiri_clickpay, bri_epay, cimb_clicks, danamon_online,
        // Ewallet = telkomsel_cash, indosat_dompetku, mandiri_ecash,
        // Over the counter = cstore
        // Cardless Credit = akulaku
        try{
            $paymentMethod = $request->input('payment_method');
            $order = Order::find($request->input('order'));
            $user = $order->user;
            $orderProduct = OrderProduct::where('order_id', $order->id)->get();

            //Apply Voucher
            if($request->input('voucher') != ''){
                $voucherAmount = (float)$request->input('voucher_amount');
                $order->voucher_code = $request->input('voucher');
                $order->voucher_amount = $voucherAmount;
                $total = $order->grand_total;
                $order->grand_total = $total - $voucherAmount;
                $order->save();
            }

            if($paymentMethod == "credit_card"){
                $order->payment_option = "Credit Card";
                $order->save();
                Log::info('Order #'. $order->order_number. ' ('.$order->id.'), User '.$user->email.' select payment Credit Card');

                //set data to request
                $transactionDataArr = Midtrans::setRequestData($order, $orderProduct, $paymentMethod);
//            dd($transactionDataArr);
//            error_log($transactionDataArr);

                //sending to midtrans
                $redirectUrl = Midtrans::sendRequest($transactionDataArr);

                //send email for notification new transaction
//                $user = User::find($order->user_id);
//                $newTransaction = new NewTransaction($user, $order, "Credit Card");
//                Mail::to(env('MAIL_SALES'))
//                    ->send($newTransaction);

                return Response::json(array('success' => $redirectUrl));
            }
            else{
                $order->payment_option = "Transfer Bank";
                $order->save();
                Log::info('Order #'. $order->order_number. ' ('.$order->id.'), User '.$user->email.' select payment Transfer Bank');

                $redirectUrl = route('checkout-transfer-information', ['order'=>$order]);
                //dd($exception);

                //Pending bank transfer payment
                //change status
                $order->order_status_id = 7;
                $order->save();

                //send email for notification new transaction
//                $user = User::find($order->user_id);
//                $newTransaction = new NewTransaction($user, $order, "Transfer Bank");
//                Mail::to(env('MAIL_SALES'))
//                    ->send($newTransaction);

                return Response::json(array('success' => $redirectUrl));
            }
        }
        catch (\Exception $ex){
//            dd($ex);
            Log::error("CheckoutController.php > submitCheckout ".$ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }
//    public function submitCheckout(Request $request, Order $order){
//        // Credit card = credit_card
//        // Bank Transfer = bank_transfer, echannel,
//        // Internet Banking = bca_klikpay, bca_klikbca, mandiri_clickpay, bri_epay, cimb_clicks, danamon_online,
//        // Ewallet = telkomsel_cash, indosat_dompetku, mandiri_ecash,
//        // Over the counter = cstore
//        // Cardless Credit = akulaku
//        try{
//            $paymentMethod = $request->input('payment_method');
//            $orderProduct = OrderProduct::where('order_id', $order->id)->get();
//
//            //set data to request
//            $transactionDataArr = Midtrans::setRequestData($order, $orderProduct, $paymentMethod);
////            dd($transactionDataArr);
//
//            //sending to midtrans
//            $redirectUrl = Midtrans::sendRequest($transactionDataArr);
//            //dd($exception);
//        }
//        catch (\Exception $ex){
////            dd($ex);
//            return 0;
//        }
//        //                dd($redirectUrl);
//
//        return redirect($redirectUrl);
//    }

    public function checkoutSuccess($order){
        try{
//            dd("asdf");
            //change order status to pending payment
            $order = str_replace('_', '/', $order);
            $orderDB = Order::where('order_number', $order)->first();
            $orderDB->order_status_id = 3;
            $orderDB->save();

            $user = User::find($orderDB->user_id);
            Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Payment Credit Card success by '.$user->email.', order status '.$orderDB->order_status->name);

            $orderProducts = OrderProduct::where('order_id', $orderDB->id)->get();

            // Create ZOHO Invoice
            $zohoResult = Zoho::createInvoice($orderDB->zoho_sales_order_id);

            if($orderDB->is_sent_email_processing == 0){
                //send email confirmation
                $productIdArr = [];
                foreach ($orderProducts as $orderProduct){
                    array_push($productIdArr, $orderProduct->product_id);

                    //minus item quantity
                    $product = $orderProduct->product;
                    $qty = $product->qty;
                    if($qty > 0){
                        $product->qty = $qty-1;
                        $product->save();
                    }
                }

                $productImages = ProductImage::whereIn('product_id',$productIdArr)->where('is_main_image', 1)->get();
                $productImageArr = [];
                foreach ($productImages as $productImage){
                    $productImageArr[$productImage->product_id] = $productImage->path;
                }

                // mengurangi stock voucher
                if(!empty($order->voucher_code)){
                    $voucher =  Voucher::where('code', strtoupper($order->voucher_code))->first();
                    if(!empty($voucher->stock)){
                        if($voucher->stock > 0){
                            $currentStock = $voucher->stock;
                            $voucher->stock = $currentStock - 1;
                            $voucher->save();
                        }
                    }
                }

                $orderConfirmation = new OrderConfirmation($user, $orderDB, $orderProducts, $productImageArr);
                Mail::to($user->email)
                    ->bcc(env('MAIL_SALES'))
                    ->send($orderConfirmation);
                Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Email sent to '.$user->email.' payment '.$orderDB->payment_option.', order status '.$orderDB->order_status->name);
                $orderDB->is_sent_email_processing = 1;
                $orderDB->save();
            }

            $data=([
                'order' => $orderDB,
                'orderProduct' => $orderProducts,
            ]);
//            dd($data);
            return view('frontend.transactions.checkout-success')->with($data);
        }
        catch(\Exception $ex){
//            dd($ex);
            error_log($ex);
            Log::error("CheckoutController > checkoutSuccess Error: ". $ex->getMessage());
        }
    }
    public function TransferInformation(Order $order){
        $orderProducts = OrderProduct::where('order_id', $order->id)->get();

        $user = User::find($order->user_id);

        $productIdArr = [];
        foreach ($orderProducts as $orderProduct){
            array_push($productIdArr, $orderProduct->product_id);

            //minus item quantity
            $product = $orderProduct->product;
            $qty = $product->qty;
            if($qty > 0){
                $product->qty = $qty-1;
                $product->save();
            }
        }

        // mengurangi stock voucher
        if(!empty($order->voucher_code)){
            $voucher =  Voucher::where('code', strtoupper($order->voucher_code))->first();
            if(!empty($voucher->stock)){
                if($voucher->stock > 0){
                    $currentStock = $voucher->stock;
                    $voucher->stock = $currentStock - 1;
                    $voucher->save();
                }
            }
        }

        //send email confirmation
        try{
            $productImages = ProductImage::whereIn('product_id',$productIdArr)->where('is_main_image', 1)->get();
            $productImageArr = [];
            foreach ($productImages as $productImage){
                $productImageArr[$productImage->product_id] = $productImage->path;
            }
            $orderConfirmation = new OrderConfirmation($user, $order, $orderProducts, $productImageArr);
            Mail::to($user->email)
                ->bcc(env('MAIL_SALES'))
                ->send($orderConfirmation);
            Log::info('Order #'. $order->order_number. ' ('.$order->id.'), Email sent to '.$user->email.' payment '.$order->payment_option.', order status '.$order->order_status->name);
        }
        catch(\Exception $ex){
            Log::info('Order #'. $order->order_number. ' ('.$order->id.'), sending email failed');
            Log::error("CheckoutController > TransferInformation Error: ". $ex->getMessage());
        }

        $data=([
            'order' => $order,
            'orderProduct' => $orderProducts,
        ]);
        return view('frontend.transactions.transfer_information')->with($data);
    }
    public function checkoutFailed(Order $order){

    }
}
