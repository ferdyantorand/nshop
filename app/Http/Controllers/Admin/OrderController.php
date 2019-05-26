<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\libs\Zoho;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderBankTransfer;
use App\Models\OrderProduct;
use App\Models\ProductImage;
use App\Models\StoreAddress;
use App\Models\User;
use App\Transformer\OrderBankTransferTransformer;
use App\Transformer\OrderTransformer;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }
}
