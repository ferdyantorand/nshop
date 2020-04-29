<?php

namespace App\libs;

use GuzzleHttp\Client;

class Moka{
    /**
     * Function to Request Token to moka App.
    */
    public static function requestToken(){
        try{
            $result = '';

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

                //Save Token
                $token = $collect->access_token;
                $refreshToken = $collect->refresh_token;
                //Save to DB
                //Token Active for 180 Days
                $result = 'Success Getting Token!';
            }
            else{
                return 'Failed getting token';
            }
            return $result;
        }
        catch (\Exception $ex){
            return null;
        }
    }
}
