<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Product;
use App\Models\Voucher;
use App\Transformer\VoucherTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class VoucherController extends Controller
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
        $users = Voucher::where('id', '!=', 3)->get();
        return DataTables::of($users)
            ->setTransformer(new VoucherTransformer)
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
        return view('admin.voucher.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.voucher.create', compact('categories'));
    }

    /**
     * Show the form for creating a new Voucher for Products.
    */
    public function createProduct(){
        $products = Product::where('id' , '!=', 1)->get();
        return view('admin.voucher.create-product', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            //Get Data from View
            $validator = Validator::make($request->all(), [
                'code'          => 'required|max:100|unique:vouchers',
                'description'   => 'required|max:100',
                'start_date'    => 'required',
                'finish_date'   => 'required'
            ]);

            //Check Category
            if($request->input('type') == 'categories'){
                $categories = $request->input('ids');
                if($categories == null){
                    return redirect()->back()->withErrors("Categories Needed!")->withInput($request->all());
                }
            }

            if($request->input('type') == 'products'){
                $products = $request->input('ids');
                if($products == null){
                    return redirect()->back()->withErrors("Products Needed!")->withInput($request->all());
                }
            }

            if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

            if(empty($request->input('voucher_amount')) && empty($request->input('voucher_percentage'))){
                return redirect()->back()->withErrors("Voucher Percentage or Voucher Amount is required!")->withInput($request->all());
            }
            //Sort out Data
            $startDate = Carbon::parse($request->input('start_date'));
            $finishDate = Carbon::parse($request->input('finish_date'));

            //Check DateTime
            if(!$finishDate->greaterThan($startDate)){
//            Session::flash('error', 'Finish Date cannot be less than Start Date!');
                return redirect()->back()->withErrors("Finish Date cannot be less than Start Date!")->withInput($request->all());
            }

            $user = Auth::guard('admin')->user();
            if($request->input('type') == 'categories'){
                //Categories
                $cats = '';
                $idx = 1;
                $categories = $request->input('ids');
                foreach ($categories as $category){
                    if($idx == count($categories)){
                        $cats .= $category;
                    }
                    else{
                        $cats .= $category . '#';
                    }
                    $idx++;
                }

                Voucher::create([
                    'code'  => strtoupper($request->input('code')),
                    'description'   => $request->input('description'),
                    'category_id'   => $cats,
                    'voucher_amount'   => $request->input('voucher_amount'),
                    'voucher_percentage'   => $request->input('voucher_percentage'),
                    'min_purchase'   => $request->input('min_purchase'),
                    'is_shipping'   => 0,
                    'repeatable'   => 0,
                    'start_date'    => $startDate,
                    'finish_date'   => $finishDate,
                    'created_at'    => Carbon::now('Asia/Jakarta'),
                    'created_by'    => $user->id,
                    'updated_at'    => Carbon::now('Asia/Jakarta'),
                    'updated_by'    => $user->id,
                    'status_id'     => $request->input('status')
                ]);
            }
            else if($request->input('type') == 'products'){
                //Categories
                $prods = '';
                $idx = 1;
                $products = $request->input('ids');
                foreach ($products as $product){
                    if($idx == count($products)){
                        $prods .= $product;
                    }
                    else{
                        $prods .= $product . '#';
                    }
                    $idx++;
                }

                Voucher::create([
                    'code'  => strtoupper($request->input('code')),
                    'description'   => $request->input('description'),
                    'product_id'   => $prods,
                    'voucher_amount'   => $request->input('voucher_amount'),
                    'voucher_percentage'   => $request->input('voucher_percentage'),
                    'min_purchase'   => $request->input('min_purchase'),
                    'is_shipping'   => 0,
                    'repeatable'   => 0,
                    'start_date'    => $startDate,
                    'finish_date'   => $finishDate,
                    'created_at'    => Carbon::now('Asia/Jakarta'),
                    'created_by'    => $user->id,
                    'updated_at'    => Carbon::now('Asia/Jakarta'),
                    'updated_by'    => $user->id,
                    'status_id'     => $request->input('status')
                ]);
            }

            Session::flash('success', 'Success Creating new Voucher!');
        }
        catch (\Exception $exception){
            Log::error("VoucherController/store error: ". $exception);
            Session::flash('error', 'Failed Creating new Voucher!');
        }
        return redirect()->route('admin.vouchers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCategory($id)
    {
        $voucher = Voucher::find($id);

        //More Data to Show
        if($voucher->category_id != null || $voucher->category_id != 0){
            $cats = explode('#', $voucher->category_id);
            $choosenCategories = Category::whereIn('id', $cats)->get();
//            dd($choosenCategories);
            $categories = Category::all();
        }
        else{
            $categories = null;
            $choosenCategories = null;
        }

        return view('admin.voucher.edit-category', compact('voucher', 'categories', 'choosenCategories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProduct($id)
    {
        $voucher = Voucher::find($id);

        if($voucher->product_id != null || $voucher->product_id != 0){
            $prods = explode('#', $voucher->product_id);
            $choosenProducts = Product::whereIn('id', $prods)->get();
            $products = Product::where('id', '!=', 1)->get();
        }
        else{
            $products = null;
            $choosenProducts = null;
        }

        return view('admin.voucher.edit-product', compact('voucher', 'products', 'choosenProducts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{
            //Get Data from View
            $validator = Validator::make($request->all(), [
                'code'          => 'required|max:100',
                'description'   => 'required|max:100',
                'start_date'    => 'required',
                'finish_date'   => 'required'
            ]);

            if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

            //Sort out Data
            $startDate = Carbon::parse($request->input('start_date'));
            $finishDate = Carbon::parse($request->input('finish_date'));

            //Check DateTime
            if(!$finishDate->greaterThan($startDate)){
                Session::flash('error', 'Finish Date cannot be less than Start Date!');
                return redirect()->back();
            }

            $user = Auth::guard('admin')->user();
            $voucher = Voucher::find($request->input('id'));
//dd($request->input('ids'));
            //check for categories or products
            $cats = null;
            $prods = null;
            if($request->input('type') == 'categories'){
                //Categories
                $cats = '';
                $idx = 1;
                $categories = $request->input('ids');
                foreach ($categories as $category){
                    if($idx == count($categories)){
                        $cats .= $category;
                    }
                    else{
                        $cats .= $category . '#';
                    }
                    $idx++;
                }
            }
            else if($request->input('type') == 'products'){
                //Categories
                $prods = '';
                $idx = 1;
                $products = $request->input('ids');
                foreach ($products as $product){
                    if($idx == count($products)){
                        $prods .= $product;
                    }
                    else{
                        $prods .= $product . '#';
                    }
                    $idx++;
                }
            }

            $voucher->code = $request->input('code');
            $voucher->voucher_amount   = $request->input('voucher_amount');
            $voucher->voucher_percentage   = $request->input('voucher_percentage');
            $voucher->min_purchase   = $request->input('min_purchase');
            $voucher->description = $request->input('description');
            $voucher->start_date = $startDate;
            $voucher->finish_date = $finishDate;
            $voucher->status_id = $request->input('status');
            $voucher->category_id = $cats;
            $voucher->product_id = $prods;
            $voucher->updated_at = Carbon::now('Asia/Jakarta');
            $voucher->updated_by = $user->id;
            $voucher->save();

            Session::flash('success', 'Success Updating Voucher ' . $voucher->code . '!');
        }
        catch (\Exception $exception){
            Log::error("VoucherController/update error: ". $exception);
            Session::flash('error', 'Failed Updating Voucher ' . $voucher->code . '!');
        }
        return redirect()->route('admin.vouchers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            //Belum melakukan pengecekan hubungan antar Table
            $voucherId = $request->input('id');
            $voucher = Voucher::find($voucherId);
            $voucher->delete();

            Session::flash('success', 'Success Deleting Voucher ' . $voucher->code);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
