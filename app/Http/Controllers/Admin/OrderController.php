<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\libs\Utilities;
use App\libs\Zoho;
use App\Mail\OrderConfirmation;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderBankTransfer;
use App\Models\OrderProduct;
use App\Models\OrderWa;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Province;
use App\Models\StoreAddress;
use App\Models\User;
use App\Models\Voucher;
use App\Transformer\OrderBankTransferTransformer;
use App\Transformer\OrderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function getIndex(Request $request){
        $users = Order::where('order_status_id', '>', 0)
            ->orderBy('order_number', 'desc')->get();
        return DataTables::of($users)
            ->setTransformer(new OrderTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    public function getIndexBankTransfer(Request $request){
        $users = OrderBankTransfer::whereIn('status', [0,1])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        return DataTables::of($users)
            ->setTransformer(new OrderBankTransferTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    public function getIndexProcessing(Request $request){
        $users = Order::where('order_status_id', 3)
            ->orderBy('order_number', 'desc')->get();
        return DataTables::of($users)
            ->setTransformer(new OrderTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    public function getIndexShipped(Request $request){
        $users = Order::where('order_status_id', 4)
            ->orderBy('order_number', 'desc')->get();
        return DataTables::of($users)
            ->setTransformer(new OrderTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.order.index');
    }

    /**
     * Display a listing of the resource bank Transfer.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBankTransfer()
    {
        return view('admin.order.index-transfer');
    }

    /**
     * Display a listing of the resource processing.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProcessing()
    {
        return view('admin.order.index-processing');
    }

    /**
     * Display a listing of the resource shipped.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexShipped()
    {
        return view('admin.order.index-shipped');
    }

    /**
     * Show the form for creating a new transaction from backend.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Province::all();
        $cities = City::all();

        // Get Rajaongkir API Key
        $roApiKey = env('RAJAONGKIR_KEY');
        $userCity = -1;
        $totalWeight = 1;

        $data = [
            'provinces'     => $provinces,
            'cities'        => $cities,
            'totalWeight'   => $totalWeight,
            'userCity'      => $userCity,
            'roApiKey'      => $roApiKey
        ];
        return view('admin.order.create')->with($data);
    }

    /**
     * store a new transaction from backend.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $test = Carbon::parse($request->input('order_date'));
//        dd($request, $test->toDateTimeString());
        try{
            $shippingPrice = $request->input('delivery_fee');
            $courier = $request->input('choose_shipping');

            $today = Carbon::now('Asia/Jakarta')->format('Ymd');
            $prepend = "INV/". $today;

            $nextNo = Utilities::GetNextOrderNumber($prepend);
            $orderNumber = Utilities::GenerateOrderNumber($prepend, $nextNo);

            //create order
            $newOrder = Order::create([
                'user_id' => 293,
                'billing_address_id' => 1,
                'shipping_option' => $courier,
                'shipping_address_id' => 1,
                'shipping_charge' => $shippingPrice,
                'payment_option' => "Transfer Bank",
                'sub_total' => 0,
                'grand_total' => 0,
                'currency_code' => "IDR",
                'order_status_id' => 3,
                'order_number' => $orderNumber,
                'dustbag_option' => 0,
                'is_sent_email_processing' => 0,
                'created_at' => Carbon::parse($request->input('order_date'))->toDateTimeString(),
                'updated_at' => Carbon::parse($request->input('order_date'))->toDateTimeString()
            ]);

            // Update auto number of Order Number
            Utilities::UpdateOrderNumber($prepend);
            $voucherCode = $request->input('voucher');
            $products = $request->input('product');
            $quantity = $request->input('quantity');
            $texts = $request->input('text');
            $positions = $request->input('position');
            $colors = $request->input('color');
            $sizes = $request->input('size');

            //create order product
            $ct=0;
            $subTotal = 0;
            foreach ($products as $product){
                $productArr = explode('#', $product);
                $productID = $productArr[0];
                $productDB = Product::find($productID);
                $totalPrice = $quantity[$ct] * $productDB->price;
                $description = "";
                if($texts[$ct] != "-"){
                    $description = "Text: ".$texts[$ct]."<br>".
//                        "Font: ".$request->input('custom-font')."<br>".
                        "Position: ".$positions[$ct]."<br>".
                        "Color: ".$colors[$ct]."<br>".
                        "Size: ".$sizes[$ct]."<br>";
                }

                $newOrderProduct = OrderProduct::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $productID,
                    'qty' => $quantity[$ct],
                    'price' => $productDB->price,
                    'grand_total' => $totalPrice,
                    'product_info' => $description,
                    'created_at' => Carbon::parse($request->input('order_date'))->toDateTimeString(),
                    'updated_at' => Carbon::parse($request->input('order_date'))->toDateTimeString()
                ]);
                $subTotal += $totalPrice;
                $ct++;
            }
            $newOrder->sub_total = $subTotal;
            $newOrder->grand_total = $subTotal + $shippingPrice;
            $newOrder->save();

            //edit voucher if using voucher
            $voucherDB = Voucher::where('code', $voucherCode)->first();
//            dd($voucherDB);
            if(!empty($voucherDB)){
                $voucherAmount = $voucherDB->voucher_amount;
                if(!empty($voucherAmount)){
                    $newSubTotal = $subTotal - $voucherAmount;
                    $newGrandTotal = $newOrder->grand_total - $voucherAmount;

                    $newOrder->voucher_amount = $voucherAmount;
                    $newOrder->sub_total = $newSubTotal;
                    $newOrder->grand_total = $newGrandTotal;
                    $newOrder->save();
                }
                $voucherPercentage = $voucherDB->voucher_percentage;
                if(!empty($voucherPercentage)){
                    $voucherPercentageAmount = ($totalPrice * $voucherPercentage) / 100;
                    $newSubTotal = $subTotal - $voucherPercentageAmount;
                    $newGrandTotal = $newOrder->grand_total - $voucherPercentageAmount;

                    $newOrder->voucher_amount = $voucherPercentageAmount;
                    $newOrder->sub_total = $newSubTotal;
                    $newOrder->grand_total = $newGrandTotal;
                    $newOrder->save();
                }
            }
//dd($newOrder);

            //save other data
            $splitedCity = explode('-', $request->input('address_city'));
            $cityId = $splitedCity[1];
            $newOrderWa = OrderWa::create([
                'order_id'              => $newOrder->id,
                'name'                  => $request->input('name'),
                'email'                 => $request->input('email'),
                'phone'                 => $request->input('phone'),
                'address_description'   => $request->input('address_description'),
                'address_street'        => $request->input('address_street'),
                'address_province'      => $request->input('address_province'),
                'address_city'          => $cityId,
                'address_postal_code'   => $request->input('address_postal_code'),
                'shipping_date'         => Carbon::parse($request->input('shipping_date'))->toDateTimeString()
            ]);

            // Create ZOHO Sales Order
            Zoho::createSalesOrder($newOrder);

            Log::info('Order #'. $newOrder->order_number. ' ('.$newOrder->id.'), Transaction successfully created');
            return redirect()->route('admin.orders.detail', ['id'=>$newOrder->id]);
        }
        catch(\Exception $ex){
            Log::error("OrderController > store ".$ex);
            Session::flash('error', 'Something Went Wrong');
            dd($ex);
            return redirect()->back()->withErrors('Internal Server Error')->withInput($request->all());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTracking(Request $request)
    {
        $orderid = $request->input('order-id');
        $orderDB = Order::find($orderid);
        $orderDB->track_code = $request->input('track_code');
        $orderDB->order_status_id = 4;
        $orderDB->save();

        return redirect()->route('admin.orders.detail', ['id'=>$orderid]);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acceptBankTransfer(Request $request)
    {
        $orderid = $request->input('accept-id');
        try {
            $orderDB = Order::find($orderid);
            $orderDB->order_status_id = 3;
            $orderDB->save();

            $orderBankDB = OrderBankTransfer::where('order_id', $orderid)->first();
            $orderBankDB->status = 1;
            $orderBankDB->save();
            Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), bank transfer confirmation by Admin');


            $orderProducts = OrderProduct::where('order_id', $orderDB->id)->get();

            // Create ZOHO Invoice
            Zoho::createInvoice($orderDB->zoho_sales_order_id);

            //send email confirmation
            $user = User::find($orderDB->user_id);

            $productIdArr = [];
            foreach ($orderProducts as $orderProduct){
                array_push($productIdArr, $orderProduct->product_id);

                //minus item quantity
//                $product = $orderProduct->product;
//                $qty = $product->qty;
//                $product->qty = $qty-1;
//                $product->save();
            }

            $productImages = ProductImage::whereIn('product_id',$productIdArr)->where('is_main_image', 1)->get();
            $productImageArr = [];
            foreach ($productImages as $productImage){
                $productImageArr[$productImage->product_id] = $productImage->path;
            }
            $orderConfirmation = new OrderConfirmation($user, $orderDB, $orderProducts, $productImageArr);
            Mail::to($user->email)
                ->bcc(env('MAIL_SALES'))
                ->send($orderConfirmation);
            Log::info('Order #'. $orderDB->order_number. ' ('.$orderDB->id.'), Email sent to '.$user->email.' payment '.$orderDB->payment_option.', order status '.$orderDB->order_status->name);

            //request type, json or from form
            $requestType = $request->input('type');
            if($requestType == "json"){
                Session::flash('success', 'Success Confirmed Bank Transfer from User ' . $user->email);
                return Response::json(array('success' => 'VALID'));
            }
            else{
                return redirect()->route('admin.orders.detail', ['id'=>$orderid]);
            }
            //
        }
        catch(\Exception $ex){
            Log::error("OrderController > acceptBankTransfer ".$ex);
            Session::flash('error', 'Something Went Wrong');
            return redirect()->route('admin.orders.bank_transfer');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        if($order->user_id == 293){
            $otherData = OrderWa::where('order_id', $order->id)->first();
            $province = Province::find($otherData->address_province)->first();
            $city = City::find($otherData->address_city)->first();
//            dd($otherData);
            return view('admin.order.show-wa', compact('order', 'otherData', 'province', 'city'));
        }

        return view('admin.order.show', compact('order'));
    }

    public function packingLabel($id)
    {
        $order = Order::find($id);
        $custDB = User::find($order->user_id);
        $custAddress = $custDB->addresses->where('primary', 1)->first();
        $namaAddress = StoreAddress::find(1);

        return view('print.packing-label', compact('custDB', 'custAddress', 'namaAddress'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $order = Order::find($id);

            $orderWa = OrderWa::where('order_id', $order->id)->first();
            if(!empty($orderWa)){
                $orderWa->delete();
            }

            $orderProducts = OrderProduct::where('order_id', $order->id)->get();
            if($orderProducts->count() > 1){
                foreach($orderProducts as $orderProduct){
                    $product = Product::find($orderProduct->product_id);
                    $productQty = $product->qty + $orderProduct->qty;
                    $product->qty = $productQty;
                    $product->save();

                    $orderProduct->delete();
                }
            }
            $order->delete();
        }
        catch(\Exception $ex){
            dd($ex);
        }

        Session::flash('success', 'Success Delete Order');
        return redirect()->route('admin.orders.index');
    }
}
