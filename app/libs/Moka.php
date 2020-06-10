<?php

namespace App\libs;

use App\Models\Configuration;
use App\Models\Product;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Moka{
    /**
     * Function to Request Token to moka App.
    */
    public static function requestToken(){
        try{
            $client = new Client();

            $request = $client->post(env('MOKA_BASE_URL').'/oauth/token',[
                'form_params' => [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => '',
                    'client_secret' => '',
                    'code'          => '',
                    'redirect_url'  => 'https://nama-official.com/'
                ]
            ]);
            if($request->getStatusCode() == 200) {
                $collect = json_decode($request->getBody());
                Log::channel('moka')->info($collect);
                //Save Token
                $token = $collect->access_token;
                $refreshToken = $collect->refresh_token;

                //Save to DB
                $configuration = Configuration::where('configuration_key', 'moka_token')->first();
                $configuration->configuration_value = $token;
                $configuration->configuration_value2 = $token;
                $configuration->save();

                //Token Active for 180 Days
                $result = 'Success Getting Token!';
            }
            else{
                return 'Failed getting token';
            }
            return $result;
        }
        catch (\Exception $ex){
            Log::channel('moka_error')->error($ex);
            return null;
        }
    }

    /**
     * Function to get Business ID
     * @param $accessToken
     * @return mixed|string
     */
    public static function getBusinessData($accessToken){
        try{
            $client = new Client([
                'Authorization' => 'Bearer ' . $accessToken
            ]);

            $request = $client->get(env('MOKA_BASE_URL').'/v1/businesses');

            if($request->getStatusCode() == 200) {
                $collect = json_decode($request->getBody());
                Log::channel('moka')->info($collect);
                return $collect;
            }
            else{
                return 'Failed getting item data!';
            }
        }
        catch (\Exception $ex){
            Log::channel('moka_error')->error($ex);
            return 'sorry something went wrong!';
        }
    }

    /**
     * Function to get Outlet Id
     * @param $accessToken
     * @param $businessId
     * @return mixed|string
     */
    public static function getOutlets($accessToken, $businessId){
        try{
            $client = new Client([
                'Authorization' => 'Bearer ' . $accessToken
            ]);

            $request = $client->get(env('MOKA_BASE_URL').'/v1/businesses/'.$businessId.'/outlets');

            if($request->getStatusCode() == 200) {
                $collect = json_decode($request->getBody());
                Log::channel('moka')->info($collect);
                return $collect;
            }
            else{
                return 'Failed getting item data!';
            }
        }
        catch (\Exception $ex){
            Log::channel('moka_error')->error($ex);
            return 'sorry something went wrong!';
        }
    }

    /**
     * Function to checkout Transaction.
     * @param $transaction
     * @param $accessToken
     * @return string
     */
    public static function checkOut($transaction, $accessToken){
        try{
            $client = new Client([
                'Authorization' => 'Bearer ' . $accessToken
            ]);

            $data = [
                'note'                      => 'website_transaction',
                'include_tax_and_gratuity'  => 'false',
                'enable_tax'                => 'false',
                'enable_gratuity'           => 'false',
                'client_created_at'         => Carbon::now('Asia/Jakarta'),
                'total_gross_sales'         => $transaction->grand_total,
                'total_net_sales'           => $transaction->grand_total,
                'total_collected'           => $transaction->grand_total,
                'amount_pay'                => $transaction->grand_total,
                'items'                     => [],
                'gratuities'                => [],
                'taxes'                     => [],
                'discounts'                 => []
            ];

            foreach ($transaction->details as $detail){
                //Add item to Array Items
                $nItem = [
                    'quantity'          => $detail->qty,
                    'item_id'           => $detail->product->moka_id,
                    'item_name'         => $detail->product->name,
                    'item_variant_id'   => $detail->product->moka_variant_id,
                    'item_variant_sku'  => $detail->product->moka_variant_sku,
                    'category_id'       => $detail->product->moka_category_id,
                    'category_name'     => $detail->product->moka_category_name,
                    'client_price'      => $detail->price,
                    'discount_amount'   => 0,
                    'tax'               => 0,
                    'gratuity'          => 0,
                    'gross_sales'       => $detail->grand_total,
                    'net_sales'         => $detail->grand_total,
                    'gratuities'        => [],
                    'taxes'             => [],
                    'discounts'         => []
                ];

                array_push($data['items'], $nItem);
            }

            $request = $client->post(env('MOKA_BASE_URL').'/v1/outlets/'. env('MOKA_OUTLET_ID') .'/checkouts',[
                'json' => [
                    'checkout' => $data
                ]
            ]);

            if($request->getStatusCode() == 200) {
                $collect = json_decode($request->getBody());
                Log::channel('moka')->info($collect);
                //Update Transaction DB
                return 'Success Checkout Transaction!';
            }
            else{
                return 'Failed checkout transaction!';
            }
        }
        catch (\Exception $ex){
            Log::channel('moka_error')->error($ex);
            return 'sorry something went wrong!';
        }
    }

    /**
     * Function to get all the Item
     * @param $accessToken
     * @return mixed|string
     */
    public static function getItems($accessToken){
        try{
            $client = new Client([
                'Authorization' => 'Bearer ' . $accessToken
            ]);

            $request = $client->get(env('MOKA_BASE_URL').'/v1/outlets/'. env('MOKA_OUTLET_ID') .'/items');

            if($request->getStatusCode() == 200) {
                $collect = json_decode($request->getBody());
                Log::channel('moka')->info($collect);
                return $collect;
            }
            else{
                return 'Failed getting item data!';
            }
        }
        catch (\Exception $ex){
            Log::channel('moka_error')->error($ex);
            return 'sorry something went wrong!';
        }
    }

    public static function ItemSynchronize($mokaStocks){
        try{
            foreach($mokaStocks as $mokaStock){
                $productDB = Product::where('moka_id', $mokaStock->id)->first();
                // moka_id using item->id
                if($productDB != null){
                    // checking stock from item->item_variants[]->in_stock
                    $productDB->qty = $mokaStock->item_variants[0]->in_stock;
                    $productDB->save();
                }
            }
            return "success";
        }
        catch (\Exception $ex){
            Log::channel('moka_error')->error($ex);
            return $ex;
        }
    }
}
