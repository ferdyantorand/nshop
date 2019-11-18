<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addCart(Request $request){
        try{
            $productDB = Product::where('slug', $request->input('slug'))->first();
            $dateTimeNow = Carbon::now('Asia/Jakarta');
            $text = "";
            $color = $request->input('custom-color');
            $color = explode("-", $color);
            $size = $request->input('custom-size');
            $size = explode("-", $size);
            $position = $request->input('custom-position-name');


            if($request->input('customize-toggle') == 'true'){
                if($request->input('custom-text') == ""){
                    $description = "";
                }
                else{
                    $text = $request->input('custom-text');
//                    $description = "Text: ".strtoupper($request->input('custom-text'))."<br>".
                    $description = "Text: ".$request->input('custom-text')."<br>".
//                        "Font: ".$request->input('custom-font')."<br>".
                        "Position: ".$position."<br>".
                        "Color: ".$color[0]."<br>".
                        "Size: ".$size[0]."<br>";
                }
            }
            else{
                $description = "";
            }
//            dd($description);

            //add cart to database
            if (Auth::check())
            {
                $cartDB = Cart::where('product_id', $productDB->id)
                    ->where('user_id', Auth::user()->id)
                    ->where('description', $description)
                    ->first();
                $cartQty = 0;
                if(!empty($cartDB)){
                    $qty = $cartDB->qty + 1;
                    $cartDB->qty = $qty;
                    $cartDB->updated_at = $dateTimeNow->toDateTimeString();
                    $cartDB->total_price = $qty * $productDB->price;
                    $cartDB->save();

                    $cartQty = $qty;
                }
                else{
                    $newCart = Cart::create([
                        'product_id' => $productDB->id,
                        'user_id' => Auth::user()->id,
                        'description' => $description,
                        'qty' => 1,
                        'price' => $productDB->price,
                        'total_price' => $productDB->price,
                        'created_at'        => $dateTimeNow->toDateTimeString(),
                        'updated_at'        => $dateTimeNow->toDateTimeString()
                    ]);
                }

                $cartsDb = Cart::where('product_id', '!=', $productDB->id)->where('user_id', Auth::user()->id)->get();
                foreach ($cartsDb as $cart){
                    $cartQty += $cart->qty;
                }

                $request->session()->put('cartQty', $cartQty);
                Session::put('cartQty', $cartQty);
            }
            //add cart to Session
            else
            {
                $oldCart = Session::has('cart') ? Session::get('cart') : null;
                $cart = new \App\Cart($oldCart);
                $tmpCart = new Cart([
                    'product_id' => $productDB->id,
                    'description' => $description,
                    'qty' => 1,
                    'price' => $productDB->price,
                    'total_price' => $productDB->price,
                    'created_at'        => $dateTimeNow->toDateTimeString(),
                    'updated_at'        => $dateTimeNow->toDateTimeString()
                ]);
                $text = str_replace('<IMG SRC="'.env('SERVER_HOST_URL_UPPERCASE').'IMAGES/EMOJI/','',$text);
                $text = str_replace('.PNG">','',$text);
                $cart->add($tmpCart, $productDB->id.$text);

                $request->session()->put('cart', $cart);
                Session::put('cart', $cart);
            }

            return redirect()->route('cart');

        }catch(\Exception $ex){
//            dd($ex);
            error_log($ex);
            Log::error("CartController/addCart error: ". $ex);
            return back()->withErrors("Something Went Wrong")->withInput($request->all());
        }
    }

    public function getCart(){
        Session::forget('cartQty');
        if (Auth::check())
        {
            //Read DB
            $user = Auth::user();
            $carts = Cart::where('user_id', $user->id)->get();
            $flag = 1;
            if(!empty($carts)){
                $totalPrice = $carts->sum('total_price');
            }
            else{
                $totalPrice = 0;
            }

            if(Session::get('cartQty') == null) {
                $cartQty = 0;
                $cartsDb = Cart::where('user_id', Auth::user()->id)->get();
                foreach ($cartsDb as $cart) {
                    $cartQty += $cart->qty;
                }
                Session::put('cartQty', $cartQty);
            }
        }
        else if(Session::has('cart')){
            $oldCart = Session::get('cart');
            $cart = new \App\Cart($oldCart);
            $carts = $cart->items;
            $totalPrice = 0;
            foreach ($carts as $item){
                $totalPrice += ($item["qty"] * $item["price"]);
            }
            $cartQty = $cart->totalQty;
            //dd($carts);
//            foreach ($carts as $item){
//                $totalPrice += ($item['price'] * $item['qty']);
//                $cartQty += $item['qty'];
//            }
//            dd($cart);
            Session::put('cartQty', $cartQty);
            $flag = 2;
            //dd($carts);
        }
        else{
            $carts = null;
            $flag = 0;
            $totalPrice = 0;
        }
        //dd($carts);
        return view('frontend.cart', compact('carts', 'flag', 'totalPrice'));
    }

    public function submitCart(Request $request)
    {
        try{
            if (Auth::check()){
                // Update Qty in DB
                $items = $request->input('id');
                $qtys = $request->input('qty');
                $voucher = $request->input('voucher');
                foreach ($items as $item){
                    $cart = Cart::find($item);
                    $product = Product::find($cart->product_id);
                    if($qtys[$item] > $product->qty){
                        return back()->withErrors("One or more product quantity exceeds that product stock");
                    }
                    $cart->qty = $qtys[$item];
                    $cart->total_price = $cart->price * $qtys[$item];
                    $cart->voucher_code = $voucher;
                    $cart->save();
                }

                $cartQty = 0;
                $cartsDb = Cart::where('user_id', Auth::user()->id)->get();
                foreach ($cartsDb as $cart) {
                    $cartQty += $cart->qty;
                }

                Session::put('cartQty', $cartQty);

                return redirect()->route('billing');
            }
            else{
                // Update Session
                $oldCart = Session::has('cart') ? Session::get('cart') : null;
                $cart = new \App\Cart($oldCart);
                $items = $request->input('id');
                $qtys = $request->input('qty');
                $voucher = $request->input('voucher');
//                dd($items);
                foreach ($items as $item){
                    $cart->update($item, $qtys[$item], $voucher);
                }
                $request->session()->put('cart', $cart);
                Session::put('cart', $cart);
                Session::put('shopping', 'yes');

                return redirect()->route('login.register');
            }
        }
        catch (\Exception $exception){
//            dd($exception);
            Log::error("CartController/submitCart error: ". $exception);
            return redirect()->route('login.register');
        }
    }

    public function deleteCart(Request $request){
        //dd($request->input('cartId'));

        try{
            //Delete Cart from DB and Cookie
            //from DB
            if (Auth::check()){
                $cart = Cart::find($request->input('cartId'));
                $cart->delete();
                $cartQty=0;
                $cartsDb = Cart::where('user_id', Auth::user()->id)->get();
                foreach ($cartsDb as $cart){
                    $cartQty += $cart->qty;
                }

                $request->session()->put('cartQty', $cartQty);
                Session::put('cartQty', $cartQty);
            }
            else{
                // delete from Session
                $oldCart = Session::has('cart') ? Session::get('cart') : null;
                $cart = new \App\Cart($oldCart);
                $cart->remove($request->input('cartId'));
//                dd($cart);
                $request->session()->put('cart', $cart);
                Session::put('cart', $cart);
            }

            return redirect()->route('cart');
        }
        catch (\Exception $exception){
            Log::error("CartController/deleteCart error: ". $exception);
        }
    }

    public function voucherValidation(Request $request){
        try{
            $user = Auth::user();
            $voucher = strtoupper($request->input('voucher-code'));

            $voucherCheck = Voucher::where('code', $voucher)->first();
            // checking if valid voucher code
            if(empty($voucherCheck)){
                return Response::json(array('errors' => 'Voucher Not Found!'));
            }
            // checking if free shipping voucher
            if($voucherCheck->is_shipping == 1){
                $order = Order::find($request->input('order_id'));
                $totalVoucher = $order->shipping_charge;

                return Response::json(array('success' => $totalVoucher));
            }
            else{
                // checking if using stock system and checking if stock available
                if($voucherCheck->is_stock == 1){
                    error_log("stock ada dalam if   ".$voucherCheck->stock);
                    if($voucherCheck->stock <= 0){
                        error_log("sisa stock ada ".$voucherCheck->stock);
                        return Response::json(array('errors' => 'Voucher Already Used Up!'));
                    }
                }

                //Check if User already used the Voucher
                $order = Order::where('user_id', $user->id)->where('voucher_code', $voucher)->first();
                if($order != null){
                    return Response::json(array('errors' => 'Voucher Already Used!'));
                }

                //Check Voucher
                $now = Carbon::now('Asia/Jakarta');
                $voucherDB = Voucher::where('code', $voucher)
                    ->where('start_date', '<=', $now)
                    ->where('finish_date', '>=', $now)->first();
                $trx = Order::find($request->input('order_id'));

                if(!empty($voucherDB)){
                    // checking for minimum purchase
                    if(!empty($voucherCheck->min_purchase)){
                        if($trx->sub_total < $voucherCheck->min_purchase){
                            return Response::json(array('errors' => 'Minimum purchase to activate this voucher is IDR '.$voucherCheck->min_purchase_string.'!'));
                        }
                    }
                    //Check the Voucher Categories or Products
                    if($voucherDB->category_id != null){
                        $cats = explode('#', $voucherDB->category_id);
                        $flag = false;
                        $totalVoucher = 0;
                        $voucherAmount = $voucherDB->voucher_amount == null ? 0 : $voucherDB->voucher_amount;
                        $voucherPercentage = $voucherDB->voucher_percentage == null ? 0 : $voucherDB->voucher_percentage;

                        foreach ($trx->order_products as $details){
                            foreach ($cats as $cat){
                                if($flag == true){
                                    break;
                                }
                                if($cat == $details->product->category_id){

                                    $flag = true;

                                    //Count the Voucher Total Price
                                    if($voucherAmount != 0){
                                        $totalVoucher += $voucherAmount;
                                    }
                                    else{
                                        $totalVoucher += $details->product->price * $voucherPercentage / 100;
                                    }
                                    break;
                                }
                            }
                        }

                        if($flag){
                            return Response::json(array('success' => $totalVoucher));
                        }
                        else{
                            return Response::json(array('errors' => 'No Suitable Product Found!'));
                        }
                    }
                    else if($voucherDB->product_id != null){
                        $prods = explode('#', $voucherDB->product_id);
                        $flag = false;
                        $totalVoucher = 0;
                        $voucherAmount = $voucherDB->voucher_amount == null ? 0 : $voucherDB->voucher_amount;
                        $voucherPercentage = $voucherDB->voucher_percentage == null ? 0 : $voucherDB->voucher_percentage;

                        foreach ($trx->order_products as $details){
                            foreach ($prods as $prod){
                                if($prod == $details->product_id){
                                    $flag = true;

                                    //Count the Voucher Total Price
                                    if($voucherAmount != 0){
                                        $totalVoucher += $voucherAmount;
                                    }
                                    else{
                                        $totalVoucher += $details->product->price * $voucherPercentage / 100;
                                    }
                                }
                            }
                        }

                        if($flag){
                            return Response::json(array('success' => $totalVoucher));
                        }
                        else{
                            return Response::json(array('errors' => 'No Suitable Product Found!'));
                        }
                    }
                    else{
                        return Response::json(array('errors' => 'Voucher Not Found!'));
                    }
                }
                else{
                    return Response::json(array('errors' => 'Voucher Not Found!'));
                }
            }
        }
        catch (\Exception $exception){
            Log::error("CartController/voucherValidation error: ". $exception);
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
