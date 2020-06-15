<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\libs\Midtrans;
use App\libs\Moka;
use App\libs\Utilities;
use App\libs\Zoho;
use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\City;
use App\Models\Configuration;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subscribe;
use App\Models\User;
use App\Models\Voucher;
use App\Models\WaitingList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return view('frontend.coming-soon');
        $products = Product::where('is_primary', 1)->where('status', 1)->orderBy('created_at', 'desc')->get();

        if (Auth::check())
        {
            if(Session::get('cartQty') == null) {
                $cartQty = 0;
                $cartsDb = Cart::where('user_id', Auth::user()->id)->get();
                foreach ($cartsDb as $cart) {
                    $cartQty += $cart->qty;
                }

                Session::put('cartQty', $cartQty);
            }
        }

        $data=([
           'products' => $products,
        ]);
        return view('frontend.home')->with($data);
    }

    public function contactForm()
    {
        return view('frontend.others.contact-us');
    }

    public function contact(Request $request)
    {
        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $newContact = ContactMessage::create([
            'name' => $request->input('first_name')." ".$request->input('last_name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'created_at'        => $dateTimeNow->toDateTimeString(),
        ]);
        return redirect()->route('home');
    }

    public function newsletter(Request $request)
    {
        try{
            $dateTimeNow = Carbon::now('Asia/Jakarta');
            $subscribeDB = Subscribe::where('name', $request->input('name'))
                ->where('email', $request->input('email'))
                ->first();
            if(empty($subscribeDB)){
                $newSubscriber = Subscribe::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'created_at'        => $dateTimeNow->toDateTimeString(),
                ]);
            }
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID' . $request->input('id')));
        }
    }

    public function waitingList(Request $request)
    {
        try{
            $productDB = Product::where('slug', $request->input('slug'))->first();
            $dateTimeNow = Carbon::now('Asia/Jakarta');

            $newSubscriber = WaitingList::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'product_id' => $productDB->id,
                'created_at'        => $dateTimeNow->toDateTimeString(),
            ]);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID' . $request->input('slug')));
        }
    }

    public function aboutUs(){
        $products = Product::where('status', 1)->where('is_primary', 1)->get();
        $data=([
            'products' => $products,
        ]);
        return view('frontend.others.about-us')->with($data);
    }

    public function Others($type){
        //faq
        if($type == 'FAQ'){
            return view('frontend.others.FAQ');
        }
        //term and condition
        else if($type == 'Term-Condition'){
            return view('frontend.others.term-condition');
        }
        //covid-19
        else if($type == 'Covid'){
            return view('frontend.others.covid');
        }
        //privacy policy
        else{
            return view('frontend.others.privacy-policy');
        }
    }

    public function getLocation(){
        dd(\Request::ip());
        $asdf = geoip($ip = \Request::ip());
        dd($asdf);
    }

    public function getProvince(){
        $uri = 'https://api.rajaongkir.com/starter/province';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.rajaongkir.com/starter/province',[
            'query' => ['key' => '49c2d8cab7d32fa5222c6355a07834d4']
        ]);
        $response = $response->getBody()->getContents();
        $currency = (array)json_decode($response);

        return $currency;
    }
    public function TestingPurpose(){
        $type = 11;
//        dd($type);
        try{
            switch ($type){
                //testing midtrans
                case 1:
                    // Credit card = credit_card
                    // Bank Transfer = bank_transfer, echannel,
                    // Internet Banking = bca_klikpay, bca_klikbca, mandiri_clickpay, bri_epay, cimb_clicks, danamon_online,
                    // Ewallet = telkomsel_cash, indosat_dompetku, mandiri_ecash,
                    // Over the counter = cstore
                    // Cardless Credit = akulaku
                    $paymentMethod = "bank_transfer";
                    $order = Order::find(24);
                    $orderProduct = OrderProduct::where('order_id', $order->id)->get();

                    //set data to request
                    $transactionDataArr = Midtrans::setRequestData($order, $orderProduct, $paymentMethod);

//                dd(json_encode($transactionDataArr));
                    //sending to midtrans
                    $redirectUrl = Midtrans::sendRequest($transactionDataArr);
                    dd($redirectUrl);
                    return redirect($redirectUrl);

                    break;

                //testing utilities create order number
                case 2:
                    // Order number generator
//                    $today = Carbon::today();
//                    $prepend = "INV/". $today->year. $today->month. $today->day;
                    $today = Carbon::now('Asia/Jakarta')->format('Ymd');
                    $prepend = "INV/". $today;
//                dd($prepend);
                    $nextNo = Utilities::GetNextOrderNumber($prepend);
                    $orderNumber = Utilities::GenerateOrderNumber($prepend, $nextNo);
                    return $orderNumber;
                    break;

                //testing main image
                case 3:
                    // Order number generator
                    $productImage = Utilities::GetProductMainImage(1);
                    return $productImage->path;
                    break;

                //testing rajaongkir
                case 4:
                    $client = new \GuzzleHttp\Client();
                    $url = "https://api.rajaongkir.com/starter/cost";
//            $url = env('RAJAONGKIR_URL').'/cost';
                    $key = env('RAJAONGKIR_KEY');

                    $response = $client->request('POST', $url, [
                        'headers' => [
                            'key' => $key
                        ],
                        'form_params' => [
                            'origin' => 152,
                            'originType' => 'city',
                            'destination' => 455,
                            'destinationType' => 'city',
                            'weight' => 2500,
                            'courier' => 'jne'
                        ]
                    ]);
//            dd($response);
                    $response = $response->getBody()->getContents();
                    $result = json_decode($response);
//            dd($result);
                    return $result;
                    break;

                //testing SUCCESS PAGE
                case 5:
                    //testing SUCCESS PAGE
                    $order = Order::find(19);
                    $orderProduct = OrderProduct::where('order_id', $order->id)->get();

                    $data=([
                        'order' => $order,
                        'orderProduct' => $orderProduct,
                    ]);
                    return view('frontend.transactions.checkout-success')->with($data);
                    break;

                //testing SEND EMAIL ORDER CONFIRMATION
                case 6:

                    $order = Order::find(120);
                    $user = User::find($order->user_id);
                    $orderProducts = OrderProduct::where('order_id', $order->id)->get();

                    $productIdArr = [];
                    foreach ($orderProducts as $orderProduct){
                        array_push($productIdArr, $orderProduct->product_id);
                    }

                    $productImages = ProductImage::whereIn('product_id',$productIdArr)->where('is_main_image', 1)->get();
                    $productImageArr = [];
                    foreach ($productImages as $productImage){
                        $productImageArr[$productImage->product_id] = $productImage->path;
                    }
                    $orderConfirmation = new OrderConfirmation($user, $order, $orderProducts, $productImageArr);
                    Mail::to($user->email)
//                        ->bcc("sales@nama-official.com")
                        ->send($orderConfirmation);
                    return 'success';
                    break;

                //testing get city rajaongkir
                case 7:
                    $uri = 'https://pro.rajaongkir.com/api/city';
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('GET', $uri,[
                        'query' => ['key' => '49c2d8cab7d32fa5222c6355a07834d4']
                    ]);
                    $response = $response->getBody()->getContents();
                    $collect = json_decode($response);

                    foreach ($collect->rajaongkir->results as $data){
                        $cityDb = City::find($data->city_id);
                        $cityDb->name = $data->type." ".$data->city_name;
                        $cityDb->save();
                    }
                    return $collect;
                    break;

                //testing get voucher information
                case 8:
                    $voucher = "DC121201-EXT";
                    $voucherDB = Voucher::where('code', $voucher)->first();
                    if(!empty($voucherDB)){
                        $voucherAmount = $voucherDB->voucher_amount == null ? 0 : $voucherDB->voucher_amount;
                        $voucherPercentage = $voucherDB->voucher_percentage == null ? 0 : $voucherDB->voucher_percentage;

                        return $voucherAmount." | ".$voucherPercentage;
                    }
                    else{
                        return "not found";
                    }
                    break;

                //testing get waybill rajaongkir
                case 9:
                    $client = new \GuzzleHttp\Client(['http_errors' => false]);

//                    $url = "https://api.rajaongkir.com/starter/cost";
                    $url = env('RAJAONGKIR_URL').'waybill';
                    $key = env('RAJAONGKIR_KEY');

                    $waybill = "020060078240919";
                    $courierArr = "jne-REG";
                    $courier = "jne";

//                    dd($url, $key, $waybill, $courier);
                    $response = $client->request('POST', $url, [
                        'headers' => [
                            'key' => $key
                        ],
                        'form_params' => [
                            'waybill' => $waybill,
                            'courier' => $courier
                        ]
                    ]);

//                    dd($response);
                    if($response->getStatusCode() === 200){
                        $responseBody = $response->getBody()->getContents();
                        $responseArr = json_decode($responseBody);
                        $manifests = $responseArr->rajaongkir->result->manifest;
                        return Response::json(
                            array(
                                'code'      => $response->getStatusCode(),
                                'manifest'       => $manifests
                            )
                        );
                    }
                    else{
                        dd("error");
                    }
                    break;

                //udpate produk ke zoho
                case 10:
                    $products = Product::where('id', '>', 152)
                        ->where('zoho_id', 'TEMP')
                        ->get();
//                    dd($products, $products->count());
                    $i=152;
                    foreach ($products as $product){
                        $tmp = Zoho::createProduct($product, $product->category->zoho_item_group_id);
                        if($tmp){
                            $test = Zoho::assignItemToGroup($product, $product->category->zoho_item_group_id, $product->category->name);
                        }
                        $i++;
                    }
                    return $i;
                    break;

                case 11:
                    $refreshToken = Moka::requestToken();
                    $mokaToken = Configuration::where("configuration_key", "moka_token")->first();
                    $mokaResult = Moka::getItems($mokaToken->configuration_value);
                    return $mokaResult;
                    break;
            }
        }
        catch (\Exception $ex){
            dd($ex);
        }

    }
    public function setCookie(){

        Cookie::queue(Cookie::make('guest-cart', "3#4", 1440));
//        $productDB = Product::find(5);
//        $description = "asdfdsaf";
//        $user_id = "";
//
//        $cookieValue = Cookie::get('guest-cart');
//        $minutes = 1440;
//
//        //if cookie contains cart data
//        if(!empty($cookieValue)){
//            $newValue = "";
//
//            //if cookie already consist a product
//            if(strpos($cookieValue, $productDB->slug) !== false){
//                $valueArr = explode(";", $cookieValue);
//                for($i=0;$i<count($valueArr);$i++){
//                    if(strpos($valueArr[$i], '|') !== false){
//                        $valueArr2 = explode("|", $valueArr[$i]);
//
//                        //if product slug = current product
//                        if($valueArr2[0] == $productDB->slug){
//                            $qty = (double)$valueArr2[2] + 1;
//                            $price = (double)$valueArr2[3];
//                            $total_price = $qty * $price;
//                            $newValue = $newValue.$valueArr2[0]."|".$valueArr2[1]."|".$qty."|".
//                                $price."|".$total_price."|".$valueArr2[5].";";
//                        }
//                        //else rewrite current data from cookie to new cookie
//                        else{
//                            $newValue = $newValue.$valueArr2[0]."|".$valueArr2[1]."|".$valueArr2[2]."|".
//                                $valueArr2[3]."|".$valueArr2[4]."|".$valueArr2[5].";";
//                        }
//                    }
//                    else{
//                        break;
//                    }
//                }
//            }
//            else{
//                $newValue = $cookieValue;
//                $description = "asdfdsaf";
//                //cookie value = product_id|user_id|qty|price|total_price|description
//                $newValue = $newValue.$productDB->slug."|".$user_id."|1|".$productDB->price."|".$productDB->price."|".$description.";";
//
//            }
//            Cookie::queue(Cookie::make('guest-cart', $newValue, $minutes));
//        }
//        //create new cookie store cart datas
//        else{
//            //cookie value = product_id|user_id|qty|price|total_price|description
//            $value = $productDB->slug."|".$user_id."|1|".$productDB->price."|".$productDB->price."|".$description.";";
//
//            Cookie::queue(Cookie::make('guest-cart', $value, $minutes));
//        }
        return redirect()->route('getCookie');
    }
    public function getCookie(){
        $cookieValue = Cookie::get('guest-cart');
        return $cookieValue;
    }

    public function downloadCatalog(){
        try{
            return Redirect::to('/storage/aidan and ice _ nama-compressed.pdf');
        }
        catch(\Exception $ex){
            return $ex;
        }
    }
}
