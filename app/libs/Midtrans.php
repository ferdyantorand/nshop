<?php
/**
 * Created by PhpStorm.
 * User: yanse
 * Date: 18-Sep-17
 * Time: 10:30 AM
 */

namespace App\libs;

use App\Models\Cart;
use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Midtrans
{
    public static function setRequestData($order, $orderProducts, $paymentMethod){
        try{
            $rateDB = Currency::where('name', $order->currency_code)->first();
            $rate = $rateDB->rate;
            $grandTotal = 0;
            //item_details
            $item_details = [];
            foreach($orderProducts as $orderProduct){
                //set item detail
                $price = (int)($orderProduct->grand_total * $rate);
                $item_detail = array(
                    'id' => $order->id."-".$orderProduct->id,
                    'price' => $price,
                    'quantity' => $orderProduct->qty,
                    'name' => $orderProduct->Product->name
                );
                $grandTotal += $price;
                array_push($item_details, $item_detail);
            }

            //add shipping as item for midtrans
            $shippingPrice = (int)($order->shipping_charge * $rate);
            $item_shipping = array(
                'id' => $order->id."-Shipping",
                'price' => $shippingPrice,
                'quantity' => 1,
                'name' => "Shipping ".$order->shipping_option
            );
            $grandTotal += $shippingPrice;
            array_push($item_details, $item_shipping);

            //add other service as item for midtrans
            if(!empty($order->payment_charge)){
                $servicePrice = (int)($order->payment_charge * $rate);
//                dd($servicePrice);
                $item_service = array(
                    'id' => $order->id."-Service",
                    'price' => $servicePrice,
                    'quantity' => 1,
                    'name' => "Service"
                );
                $grandTotal += $servicePrice;
                array_push($item_details, $item_service);
            }

            //add tax as item for midtrans
            if(!empty($order->tax_amount)){
                $taxPrice = (int)($order->tax_amount * $rate);
                $item_tax = array(
                    'id' => $order->id."-Tax",
                    'price' => $taxPrice,
                    'quantity' => 1,
                    'name' => "Tax"
                );
                $grandTotal += $taxPrice;
                array_push($item_details, $item_tax);
            }

            //add Voucher
            if(!empty($order->voucher_amount)){
                $voucherAmount = (int)$order->voucher_amount;
                $voucher = array(
                    'id'        => $order->id."-Voucher",
                    'price'     => -$voucherAmount,
                    'quantity'  => 1,
                    'name'      => 'Voucher'
                );
                $grandTotal -= $voucherAmount;
                array_push($item_details, $voucher);
            }

            //vtweb

            // credit card = credit_card
            // bank transfer = bank_transfer
            // e-wallet =
            // direct debit = mandiri_clickpay, cimb_clicks, bri_epay, bca_klikpay

            $hostUrl = env('SERVER_HOST_URL');
            if($paymentMethod == 'bank_transfer'){
                $finish_redirect_url = $hostUrl. 'checkout/success/bank_transfer';
                $unfinish_redirect_url = $hostUrl. 'checkout-4';
            }
            else{
                $orderNumber = str_replace('/', '_', $order->order_number);

                $finish_redirect_url = $hostUrl. 'checkout-success/'.$orderNumber;
                $unfinish_redirect_url = $hostUrl. 'checkout-failed/'.$orderNumber;
            }

            $paymentList = [];
            $paymentList[] = $paymentMethod;
//            array_push($paymentList, $paymentMethod);

            $vt_web = array(
                'credit_card_3d_secure' => true,
//                'enabled_payments' => $paymentList,
                'finish_redirect_url' => $finish_redirect_url,
                'unfinish_redirect_url' => $unfinish_redirect_url,
                'error_redirect_url' => $hostUrl. 'payment/error'
            );

            $transaction_details = array(
                'order_id' => $order->order_number,
                'gross_amount' => $grandTotal, // no decimal allowed
            );

            $customer_details = array(
                'email'         => $order->user->email,
                'phone'         => $order->user->phone
            );

            $transaction = array(
                'payment_type' => "vtweb",
                'vtweb' => $vt_web,
                'transaction_details' => $transaction_details,
                'item_details' => $item_details,
                'customer_details' => $customer_details,
                'enabled_payments' => $paymentList,
                'credit_card' => [
                    "secure" => true
                ],
            );
            return $transaction;
        }
        catch (\Exception $ex){
//            error_log($ex);
            Log::error("Midtrans.php > midtransSetRequestData ".$ex);
            Utilities::ExceptionLog('midtransSetRequestData EX = '. $ex);
        }
    }

    public static function sendRequest($transactionDataArr){
        try{
            $isDevelopment = env('MIDTRANS_IS_DEVELOPMENT');

            if($isDevelopment == "true"){
                $serverKey = env('MIDTRANS_SERVER_KEY_SANDBOX');
                $serverURL = env('MIDTRANS_URL_SANDBOX');
                $serverSnapURL = env('MIDTRANS_SNAP_URL_SANDBOX');
            }
            else{
                $serverKey = env('MIDTRANS_SERVER_KEY');
                $serverURL = env('MIDTRANS_URL_PRODUCTION');
                $serverSnapURL = env('MIDTRANS_SNAP_URL_PRODUCTION');
            }
            json_encode($transactionDataArr);
//            $transactionDataJSON = json_encode($transactionDataArr);

            $base64ServerKey = base64_encode($serverKey);

            $client = new Client([
                'base_uri' => $serverSnapURL,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.$base64ServerKey
                ],
            ]);
            $request = $client->request('POST', $serverSnapURL, [
                'json' => $transactionDataArr
            ]);

//            dd($request);
//            Utilities::ExceptionLog($request->getBody());

            if($request->getStatusCode() == 200 || $request->getStatusCode() == 201){
                $collect = json_decode($request->getBody());
                $redirectUrl = $collect->redirect_url;
                $token = $collect->token;

                return $token;
            }
//            if($request->getStatusCode() == 200){
//                $collect = json_decode($request->getBody());
////                dd($request);
//                if($collect->status_code == 201){
//                    $redirectUrl = $collect->redirect_url;
//                }
//                else{
//                    $redirectUrl = "";
//                }
//                return $redirectUrl;
//            }
        }
        catch (\Exception $ex){
//            error_log($ex);
//            dd($ex);
            Log::error("Midtrans.php > midtransSendRequest ".$ex);
        }
    }
}
