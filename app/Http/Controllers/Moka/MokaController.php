<?php


namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class MokaController extends Controller
{
    public function getCode(Request $request){
        try{
            //This is the unique Code
            //Need to save it to Database to Request Token
            $code = $request->query('code');
        }
        catch (\Exception $ex){

        }
    }
}
