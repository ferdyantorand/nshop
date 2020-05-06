<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\libs\Moka;
use App\libs\Utilities;
use App\libs\Zoho;
use App\Models\Address;
use App\Models\Cart;
use App\Models\City;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Province;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    public function getBilling()
    {
        // Have to Check to if User already Login
        $flag=0;
        $address = null;
        $user = null;
        $totalWeight = 0;

        $userCity = -1;
        if (Auth::check())
        {
            error_log("Auth::check");
            //Read DB
            $user = Auth::user();
            $addressDB = Address::where('user_id', $user->id)->get();
            if(count($addressDB) != 0){
                $address = Address::where('user_id', $user->id)->where('primary', 1)->first();
                $userCity = $address->city_id;
                $flag=1;
            }
            $cartDB = Cart::where('user_id', $user->id)->first();
            if(!empty($cartDB)){
                error_log("if cartDB");
                $cartDB = Cart::where('user_id', $user->id)->get();
                foreach ($cartDB as $cart){
                    $weight = $cart->product->weight * $cart->qty;
                    $totalWeight += $weight;
                }
            }
            else if(Session::has('cart')){
                error_log("if session cart");
                    $oldCart = Session::has('cart') ? Session::get('cart') : null;
                    $cart = new \App\Cart($oldCart);
                    $carts = $cart->items;

                    foreach ($carts as $cartData) {
//                        dd($carts);
                        $productDB = Product::find($cartData['item']['product_id']);
                        Cart::create([
                            'product_id' => $cartData['item']['product_id'],
                            'user_id' => $user->id,
                            'description' => $cartData['item']['description'],
                            'qty' => $cartData['item']['qty'],
                            'price' => $cartData['item']['price'],
                            'total_price' => $cartData['price'],
                            'created_at' => Carbon::now('Asia/Jakarta'),
                            'updated_at' => Carbon::now('Asia/Jakarta')
                        ]);


                        $weight = $productDB->weight *  $cartData['item']['qty'];
                        $totalWeight += $weight;
                    }
            }
        }
        else if(Session::has('cart')){
//            error_log("else if session cart");
            //guest has address
            if(Session::has('user')){
                $user = Session::get('user');
                $addressDB = Address::where('user_id', $user->id)->get();
                if(count($addressDB) != 0){
                    $address = Address::where('user_id', $user->id)->where('primary', 1)->first();
                    $userCity = $address->city_id;
                    $flag = 2;
                }
            }
            else{
                $oldCart = Session::has('cart') ? Session::get('cart') : null;
                $cart = new \App\Cart($oldCart);
                $carts = $cart->items;

                foreach ($carts as $cartData) {
                    $productDB = Product::find($cartData['item']['product_id']);

                    $weight = $productDB->weight *  $cartData['item']['qty'];
                    $totalWeight += $weight;
                }
            }
        }
        error_log("total weight = ".$totalWeight);

//        if(!empty($user)){
//            //get cart total weight for rajaongkir process
//            $cartDB = Cart::where('user_id', $user->id)->get();
//            foreach ($cartDB as $cart){
//                $weight = $cart->product->weight * $cart->qty;
//                $totalWeight += $weight;
//            }
//        }

        $countries = Country::all();
        $provinces = Province::all();
        $cities = City::all();
        $isIndonesian = true;
//        dd($totalWeight);

        // Get Rajaongkir API Key
        $roApiKey = env('RAJAONGKIR_KEY');

        Session::forget('cart');

        $data = [
            'flag'          => $flag,
            'address'       => $address,
            'countries'     => $countries,
            'provinces'     => $provinces,
            'cities'        => $cities,
            'totalWeight'   => $totalWeight,
            'isIndonesian'  => $isIndonesian,
            'userCity'      => $userCity,
            'roApiKey'      => $roApiKey
        ];
//        dd($data);
        return view('frontend.transactions.billing-shipment')->with($data);
    }

    public function submitBilling(Request $request)
    {
        // Get Data from Session or DB and then Continue
        // Create Transaction
        // Check Delivery Fee from Rajaongkir or DHL
        // Clear Session
        try{
            $user = null;
            $userAddress = null;
            $cityId = $request->input('city');
            $dateTimeNow = Carbon::now('Asia/Jakarta');

            if($request->input('another_shipment') == true){
                $cityIdSecondary = $request->input('city');

                if($cityIdSecondary == '-1'){
                    return redirect()->back()->withErrors('Mohon pilih kota!', 'default')->withInput($request->all());
                }

                if(strpos($cityIdSecondary, '-') !== false){
                    $splitedCity2 = explode('-', $cityIdSecondary);
                    $cityIdSecondary = $splitedCity2[1];
                }
            }
            if($cityId == '-1'){
                return redirect()->back()->withErrors('Mohon pilih kota!', 'default')->withInput($request->all());
            }

            if(strpos($cityId, '-') !== false){
                $splitedCity = explode('-', $cityId);
                $cityId = $splitedCity[1];
            }

            $totalWeight = $request->input('weight');

            // Get Data from Session
            if(!Auth::check()){
                if(Session::has('cart')) {

                    //checking if guest already have account from session
                    //dd(!Session::has('user'));
                    if(!Session::has('user')) {
                        // Validation for Users
                        $validator = Validator::make($request->all(), [
                            'first_name'        => 'required|max:100',
                            'last_name'         => 'required|max:100',
                            'email'             => 'required|regex:/^\S*$/u|unique:users|max:50',
//                        'role_id'           => 'required'
                        ],[
                            'email.unique'      => 'ID Login Akses telah terdaftar!',
                            'email.regex'       => 'ID Login Akses harus tanpa spasi!'
                        ]);
                        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                    }
                    else{
                        $user = Session::get('user');
                    }

                    //checking if guest already have address
                    $addressDB = Address::where('user_id', $user->id)->get();
                    if(count($addressDB) != 0){
                        $userAddress = Address::where('user_id', $user->id)->where('primary', 1)->first();
                    }
                    else{
                        // Create Address
                        $userAddress = Address::create([
                            'user_id' => $user->id,
                            'primary' => 1,
                            'description' => $request->input('address_detail'),
                            'country_id' => $request->input('country'),
                            'province_id' => $request->input('province'),
                            'city_id' => $cityId,
                            'street' => $request->input('street'),
                            'suburb' => $request->input('suburb'),
                            'state' => $request->input('state'),
                            'postal_code' => $request->input('post_code')
                        ]);
                    }

                    // Save to DB Table Cart
                    $oldCart = Session::has('cart') ? Session::get('cart') : null;
                    $cart = new \App\Cart($oldCart);
                    $carts = $cart->items;

                    foreach ($carts as $cartData) {
//                        dd($carts);
                        Cart::create([
                            'product_id' => $cartData['item']['product_id'],
                            'user_id' => $user->id,
                            'description' => $cartData['item']['description'],
                            'qty' => $cartData['item']['qty'],
                            'price' => $cartData['item']['product_id'],
                            'total_price' => $cartData['price'],
                            'created_at' => Carbon::now('Asia/Jakarta'),
                            'updated_at' => Carbon::now('Asia/Jakarta')
                        ]);

                        if($totalWeight == 0){
                            $weight = $cartData['item']['weight'] *  $cartData['item']['qty'];
                            $totalWeight += $weight;
                        }
                    }
                    Session::forget('cart');
                    // Session for Cart deleted
                    // Session for user Created to be used later for payment at Checkout
                    // and Shipment for Rajaongkir or DHLl
                    if(!Session::has('user')) {
                        $request->session()->put('user', $user);
                    }
                }
            }

            // Get Data from DB
            else{
                $user = Auth::user();
                $userCart = Cart::where('user_id', $user->id)->count();

                $shopping = Session::get('shopping');
                //New Conditions
                if($shopping != null){
                    $oldCart = Session::has('cart') ? Session::get('cart') : null;
                    if(!empty($oldCart)){
                        $cart = new \App\Cart($oldCart);
//                    dd($cart);
                        // Save to DB Table Cart if cart is empty
                        if($userCart == 0){
                            $carts = $cart->items;

                            foreach ($carts as $cartData) {
                                Cart::create([
                                    'product_id' => $cartData['item']['product_id'],
                                    'user_id' => $user->id,
                                    'description' => $cartData['item']['description'],
                                    'qty' => $cartData['item']['qty'],
                                    'price' => $cartData['item']['product_id'],
                                    'total_price' => $cartData['price'],
                                    'created_at' => Carbon::now('Asia/Jakarta'),
                                    'updated_at' => Carbon::now('Asia/Jakarta')
                                ]);

                                if($totalWeight == 0){
                                    $weight = $cartData['item']['weight'] *  $cartData['item']['qty'];
                                    $totalWeight += $weight;
                                }
                            }
                        }
                        // update DB Table Cart
                        else{
                            $carts = $cart->items;
                            foreach ($carts as $cartData) {
                                $cartDB = Cart::where('product_id', $cartData['item']['product_id'])->where('user_id', $user->id)->first();
                                if(!empty($cartDB)){
//                                dd($totalWeight);
                                    $qty = $cartDB->qty + 1;
                                    $cartDB->qty = $qty;
                                    $cartDB->updated_at = $dateTimeNow->toDateTimeString();
                                    $cartDB->total_price = $qty * $cartData['item']['price'];
                                    $cartDB->save();

                                    if($totalWeight == 0){
                                        $weight = $cartData['item']['weight'] *  $cartData['item']['qty'];
                                        $totalWeight += $weight;
                                    }
                                }
                            }
                        }
                    }
                }

                //checking if user already have address
                $addressDB = Address::where('user_id', $user->id)->get();
                if(count($addressDB) != 0){
                    $userAddress = Address::where('user_id', $user->id)->where('primary', 1)->first();
                }
                else{
                    // Create Address
                    $description = $request->input('address_detail').", ".$request->input('address_detail_2');
                    $userAddress = Address::create([
                        'user_id' => $user->id,
                        'primary' => 1,
                        'description' => $description,
                        'country_id' => $request->input('country'),
                        'province_id' => $request->input('province'),
                        'city_id' => $cityId,
                        'street' => $request->input('street'),
                        'suburb' => $request->input('suburb'),
                        'state' => $request->input('state'),
                        'postal_code' => $request->input('post_code')
                    ]);

//                    Zoho::updateUser(User::find($user->id));
                }

                Session::forget('cartQty');
            }

            //add new secondary address
            if($request->input('another_shipment') == true){

                $addressDB = Address::where('user_id', $user->id)->get();
                if(count($addressDB) != 0){
                    $userAddress1 = Address::where('user_id', $user->id)->where('primary', 1)->first();
                    $userAddress1->delete();

                    $userAddress = Address::create([
                        'user_id' => $user->id,
                        'primary' => 1,
                        'description' => $request->input('address_detail_secondary'),
                        'country_id' => $request->input('country_secondary'),
                        'province_id' => $request->input('province_secondary'),
                        'city_id' => $cityIdSecondary,
                        'street' => $request->input('street_secondary'),
                        'suburb' => $request->input('suburb_secondary'),
                        'state' => $request->input('state_secondary'),
                        'postal_code' => $request->input('post_code_secondary')
                    ]);
                }
                else{
                    // Create Address
                    $userAddress = Address::create([
                        'user_id' => $user->id,
                        'primary' => 1,
                        'description' => $request->input('address_detail_secondary'),
                        'country_id' => $request->input('country_secondary'),
                        'province_id' => $request->input('province_secondary'),
                        'city_id' => $cityIdSecondary,
                        'street' => $request->input('street_secondary'),
                        'suburb' => $request->input('suburb_secondary'),
                        'state' => $request->input('state_secondary'),
                        'postal_code' => $request->input('post_code_secondary')
                    ]);
                }
            }
//            dd($userAddress);

            // get rajaongkir Data
            $courier = $request->input('courier');
            $selectedCourier = explode('-',$courier);
            $shippingPrice = 0;

            if($selectedCourier[0] == "gojek"){
                $shippingPrice = 0;
            }

            else if($userAddress->city_id == 54 || $userAddress->city_id == 55 || $userAddress->city_id == 78
                || $userAddress->city_id == 79 || $userAddress->city_id == 115 || $userAddress->city_id == 151
                || $userAddress->city_id == 152 || $userAddress->city_id == 153 || $userAddress->city_id == 154
                || $userAddress->city_id == 155 || $userAddress->city_id == 455 || $userAddress->city_id == 456 ||
                $userAddress->city_id == 457)
            {
                $shippingPrice = 0;
            }
            else{
//            dd($totalWeight, $selectedCourier, $userAddress);
                $data = array();
                $data = $this->getRajaongkirData($totalWeight, $selectedCourier, $userAddress);
//            dd($data);
                if(empty($data)){
                    return redirect()->back()->withErrors("Shipping Service Not Available")->withInput($request->all());
                }

                $results = array();
                $results = $data->rajaongkir->results;
//            dd($results);
                foreach($results as $result){
                    foreach ($result->costs as $cost){
                        if($selectedCourier[0] == "jne"){
                            if($selectedCourier[1] == "REG"){
                                if($cost->service == "REG" || $cost->service == "CTC"){
                                    $shippingPrice = $cost->cost[0]->value;
                                }
                            }
                            else if($selectedCourier[1] == "YES"){
                                if($cost->service == "YES" || $cost->service == "CTCYES"){
                                    $shippingPrice = $cost->cost[0]->value;
                                }
                            }
                        }
                        else{
                            if($cost->service == $selectedCourier[1]){
                                $shippingPrice = $cost->cost[0]->value;
                            }
                        }
                    }
                }
//            dd($shippingPrice);
                if($shippingPrice == 0){
                    return redirect()->back()->withErrors("Please Wait")->withInput($request->all());
                }
            }

            // create transaction from setTransaction
            $transactionSuccess = $this->setTransaction($user, $userAddress, $courier, $shippingPrice);
            if($transactionSuccess > 0){
                // Redirect to Checkout
                Session::forget('cart');
                Session::put('cartQty', 0);
                Session::forget('shopping');
                return redirect()->route('checkout', ['order'=>$transactionSuccess]);
            }
            else{
                return back()->withErrors("Something Went Wrong")->withInput($request->all());
            }

        }
        catch (\Exception $exception){
//            dd($exception);
            Log::error("BillingController - submitBilling Error: ". $exception->getMessage());
            return redirect()->route('cart')->withErrors($exception);
//            return redirect()->route('cart')->withErrors("Something Went Wrong");
        }
    }

    public function getRajaongkirData($totalWeight, $selectedCourier, $userAddress){
        $result = array();
        try{
            $client = new \GuzzleHttp\Client();
//            $url = "https://api.rajaongkir.com/starter/cost";
            $url = env('RAJAONGKIR_URL').'cost';
            $key = env('RAJAONGKIR_KEY');

            $response = $client->request('POST', $url, [
                'headers' => [
                    'key' => $key
                ],
                'form_params' => [
                    'origin' => 152,
                    'originType' => 'city',
                    'destination' => $userAddress->city_id,
                    'destinationType' => 'city',
                    'weight' => $totalWeight,
                    'courier' => $selectedCourier[0]
                ]
            ]);
//            dd($response);
            $response = $response->getBody()->getContents();
            $result = json_decode($response);
//            dd($result);
            return $result;
        }
        catch (\Exception $exception){
            Log::error(
                "BillingController - getRajaongkirData Data: totalWeight = ".$totalWeight." | selectedCourier = ".$selectedCourier);
            Log::error("BillingController - getRajaongkirData Error: ". $exception->getMessage());
            //dd($exception);
            return $result;
        }
    }

    public function setTransaction($user, $userAddress, $courier, $shippingPrice){
        try{
            $carts = Cart::where('user_id', $user->id)->get();
            $totalPrice = $carts->sum('total_price');

            //checking moka stock, and update stock
            $mokaToken = Configuration::where("configuration_key", "moka_token")->first();
            $mokaStock = Moka::getItems($mokaToken->configuration_value);
            $productSync = Moka::ItemSynchronize($mokaStock);


            //validasi qty produk
            foreach ($carts as $cart){
                $product = Product::find($cart->product_id);
                if($cart->qty > $product->qty){
                    return 0;
                }
            }

            //$tmpDate = Carbon::now()->format("Y-m-d\TH:i:s\.000\Z");
            //dd($tmpDate);

            // Order number generator
//            $today = Carbon::today();
//            $prepend = "INV/". $today->year. $today->month. $today->day;
            $today = Carbon::now('Asia/Jakarta')->format('Ymd');
            $prepend = "INV/". $today;

            $nextNo = Utilities::GetNextOrderNumber($prepend);
            $orderNumber = Utilities::GenerateOrderNumber($prepend, $nextNo);
            $grandTotal = $totalPrice + $shippingPrice;
            //create order
            $newOrder = Order::create([
                'user_id' => $user->id,
                'billing_address_id' => $userAddress->id,
                'shipping_option' => $courier,
                'shipping_address_id' => $userAddress->id,
                'shipping_charge' => $shippingPrice,
                'payment_option' => "default",
                'sub_total' => $totalPrice,
                'grand_total' => $grandTotal,
                'currency_code' => "IDR",
                'order_status_id' => 1,
                'order_number' => $orderNumber,
                'is_sent_email_processing' => 0,
                'created_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ]);

            // Update auto number of Order Number
            Utilities::UpdateOrderNumber($prepend);
            $voucherCode = "";
            //create order product
            foreach ($carts as $cart){
                $newOrderProduct = OrderProduct::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $cart->product_id,
                    'qty' => $cart->qty,
                    'price' => $cart->price,
                    'grand_total' => $cart->total_price,
                    'product_info' => $cart->description,
                    'created_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                    'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ]);
                $newOrder->voucher_code = $cart->voucher_code;
                $voucherCode = $cart->voucher_code;
                $newOrder->save();
                $cart->delete();
            }

            //edit voucher if using voucher
            $voucherDB = Voucher::where('code', $voucherCode)->first();
//            dd($voucherDB);
            if(!empty($voucherDB)){
                $voucherAmount = $voucherDB->voucher_amount;
                if(!empty($voucherAmount)){
                    $newSubTotal = $totalPrice - $voucherAmount;

                    $newOrder->voucher_amount = $voucherAmount;
                    $newOrder->sub_total = $newSubTotal;
                    $newOrder->grand_total = $newSubTotal;
                    $newOrder->save();
                }
                $voucherPercentage = $voucherDB->voucher_percentage;
                if(!empty($voucherPercentage)){
                    $voucherPercentageAmount = ($totalPrice * $voucherPercentage) / 100;
                    $newSubTotal = $totalPrice - $voucherPercentageAmount;

                    $newOrder->voucher_amount = $voucherPercentageAmount;
                    $newOrder->sub_total = $newSubTotal;
                    $newOrder->grand_total = $newSubTotal;
                    $newOrder->save();
                }
            }
//dd($newOrder);

            // Create ZOHO Sales Order
            Zoho::createSalesOrder($newOrder);

            Log::info('Order #'. $newOrder->order_number. ' ('.$newOrder->id.'), Transaction successfully created');
            return $newOrder->id;
        }
        catch (\Exception $exception){
//            dd($exception);
            error_log($exception);
            Log::error("BillingController - setTransaction Error: ". $exception->getMessage());
            return 0;
        }
    }
}
