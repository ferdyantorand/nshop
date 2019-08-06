<?php
/**
 * Created by PhpStorm.
 * User: YANSEN
 * Date: 12/10/2018
 * Time: 10:06
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\libs\Utilities;
use App\libs\Zoho;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPosition;
use App\Transformer\ProductTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
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
        $users = Product::all();
        return DataTables::of($users)
            ->setTransformer(new ProductTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    public function index()
    {
        return view('admin.product.index');
    }

    public function show(Product $item)
    {
        $images = ProductImage::where('product_id', $item->id)->orderby('is_main_image','desc')->get();
        $productCategory = CategoryProduct::where('product_id', $item->id)->first();
        $productPosition = ProductPosition::where('product_id', $item->id)->get();

        $data = [
            'product'    => $item,
            'productCategory'    => $productCategory,
            'productPosition'    => $productPosition,
            'images'    => $images,
        ];
        return view('admin.product.show')->with($data);
    }

    public function create()
    {
        $product = Product::find(1);
        $categories = Category::all();

        $data = [
            'categories'    => $categories,
            'product'    => $product,
        ];
        return view('admin.product.create')->with($data);
    }

    public function createCopyProduct($item)
    {
        $product = Product::find($item);
        $categories = Category::all();

        $data = [
            'categories'    => $categories,
            'product'    => $product,
        ];
        return view('admin.product.create')->with($data);
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name'        => 'required',
                'sku'         => 'required',
                'category'             => 'required',
                'price'             => 'required',
                'qty'             => 'required',
                'weight'             => 'required',
                'description'             => 'required',
                'tags'             => 'required',
            ]);


            if ($request->input('category') == "-1") {
                return back()->withErrors("Category is required")->withInput($request->all());
            }
//            dd($request);
            $detailImages = $request->file('detail_image');
            $mainImages = $request->file('main_image');
            $thumbnailImages = $request->file('thumbnail_image');

            if($detailImages == null){
                return back()->withErrors("Detail Image required")->withInput($request->all());
            }
            if ($validator->fails())
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

            $route = DB::transaction(function() use ($request, $detailImages, $mainImages, $thumbnailImages){
                $dateTimeNow = Carbon::now('Asia/Jakarta');
                $slug = Utilities::CreateProductSlug($request->input('name'));

//            dd($slug);
                // save product
                $colourNew = Utilities::CreateProductSlug($request->input('colour'));
                $is_primary = 1;

                if($request->input('is_customize') === 'on'){
                    $customize = 1;
                }
                else{
                    $customize = 0;
                }
//            dd($colourNew);
                $newProduct = Product::create([
                    'name' => $request->input('name'),
                    'slug' => $slug."--".$colourNew,
                    'sku' => $request->input('sku'),
                    'category_id' => $request->input('category'),
                    'description' => $request->input('description'),
                    'style_notes' => $request->input('style_notes'),
                    'qty' => $request->input('qty'),
                    'price' => (double) $request->input('price'),
                    'colour' => $request->input('colour'),
                    'weight' => $request->input('weight'),
                    'width' => $request->input('width'),
                    'height' => $request->input('height'),
                    'length' => $request->input('length'),
                    'tag' => $request->input('tags'),
                    'is_primary' => $is_primary,
                    'is_customize' => $customize,
                    'status' => 1,
                    'created_at'        => $dateTimeNow->toDateTimeString(),
                    'updated_at'        => $dateTimeNow->toDateTimeString(),
                    'zoho_id'           => 'TEMP'
                ]);


                // save product position
                $newProductPosition = ProductPosition::create([
                    'product_id' => $newProduct->id,
                    'name' => "Top",
                    'pos_x' => 250,
                    'pos_y' => 300,
                ]);

                // save product main image, thumbnail and image detail
                //main image
                $img = Image::make($mainImages);
                $extStr = $img->mime();
                $ext = explode('/', $extStr, 2);
                $filename = $newProduct->id.'_main_'.$slug.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

                //thumbnail image
                $imgThumbnail = Image::make($thumbnailImages);
                $extStrThumbnail = $img->mime();
                $extThumbnail = explode('/', $extStrThumbnail, 2);
                $filenameThumbnail = $newProduct->id.'_thumbnail_'.$slug.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $extThumbnail[1];

                if(env('SERVER_HOST_URL') == 'http://localhost:8000/'){
                    $img->save(public_path('storage/products/'. $filename), 75);
                    $imgThumbnail->save(public_path('storage/products/'. $filenameThumbnail), 75);
                }
                else{
                    $img->save('../public_html/storage/products/'. $filename, 75);
                    $imgThumbnail->save('../public_html/storage/products/'. $filenameThumbnail, 75);
                }

                $newProductImage = ProductImage::create([
                    'product_id' => $newProduct->id,
                    'path' => $filename,
                    'is_main_image' => 1,
                    'is_thumbnail' => 0,
                ]);

                $newProductImageThumbnail = ProductImage::create([
                    'product_id' => $newProduct->id,
                    'path' => $filenameThumbnail,
                    'is_main_image' => 0,
                    'is_thumbnail' => 1,
                ]);

                //image detail
                for($i=0;$i<sizeof($detailImages);$i++){
                    $img = Image::make($detailImages[$i]);
                    $extStr = $img->mime();
                    $ext = explode('/', $extStr, 2);

                    $filename = $newProduct->id.'_'.$i.'_'.$slug.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

                    if(env('SERVER_HOST_URL') == 'http://localhost:8000/'){
                        $img->save(public_path('storage/products/'. $filename), 75);
                    }
                    else{
                        $img->save('../public_html/storage/products/'. $filename, 75);
                    }

                    $newProductImage = ProductImage::create([
                        'product_id' => $newProduct->id,
                        'path' => $filename,
                        'is_main_image' => 0,
                        'is_thumbnail' => 0,
                    ]);
                }

                // Create ZOHO Product
                $tmp = Zoho::createProduct($newProduct, $newProduct->category->zoho_item_group_id);
//            $tmp = Zoho::createProduct($newProduct, "1783013000000069095");
//            dd($tmp);

                $productPositionId =  $newProductPosition->id;
                $newProductId = $newProduct->id;

                if($customize == 1){
                    Log::error("Admin/ProductController checkpoint : customize = ". $customize);
                    return route('admin.product.edit.customize', ['item' => $productPositionId]);
                }
                else{
                    Log::error("Admin/ProductController checkpoint : customize = ". $customize);
                    return route('admin.product.show', ['item' => $newProductId]);
                }
            });
            return redirect()->away($route);
        }
        catch(\Exception $ex){
            error_log($ex);
            Log::error("Admin/ProductController error: ". $ex->getMessage());
            return back()->withErrors("Something Went Wrong")->withInput();
        }
    }

    public function createCustomize(Product $item)
    {
        $mainImage = ProductImage::where('product_id', $item->id)->where('is_main_image', 1)->first();
        $data = [
            'product'    => $item,
            'mainImage'    => $mainImage,
        ];
        return view('admin.product.create-customize')->with($data);
    }

    public function storeCustomize(Request $request, Product $item)
    {
//        dd($item);
        try{
            $validator = Validator::make($request->all(), [
                'position_name'        => 'required',
                'position_x'         => 'required',
                'position_y'             => 'required',
            ]);

            if ($validator->fails())
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

            // save product position
            $dateTimeNow = Carbon::now('Asia/Jakarta');
            $newProductCustomize = ProductPosition::create([
                'product_id' => $item->id,
                'name' => $request->input('position_name'),
                'pos_x' => $request->input('position_x'),
                'pos_y' => $request->input('position_y'),
            ]);

            return redirect()->route('admin.product.show',['item' => $item->id]);

        }catch(\Exception $ex){
            dd($ex);
            error_log($ex);
            return back()->withErrors("Something Went Wrong")->withInput();
        }
    }

    public function editCustomize($item)
    {
        $productPosition = ProductPosition::find($item);
        $mainImage = ProductImage::where('product_id', $productPosition->product_id)->where('is_main_image', 1)->first();
        $data = [
            'productPosition'    => $productPosition,
            'mainImage'    => $mainImage,
        ];
        Log::error("Admin/ProductController editCustomize checkpoint : product position ID = ". $item);
        return view('admin.product.edit-customize')->with($data);
    }

    public function updateCustomize(Request $request, ProductPosition $item)
    {
        try{
            $validator = Validator::make($request->all(), [
                'position_name'        => 'required',
                'position_x'         => 'required',
                'position_y'             => 'required',
            ]);

            if ($validator->fails())
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

            // save product position
            $dateTimeNow = Carbon::now('Asia/Jakarta');
            $item->name = $request->input('position_name');
            $item->pos_x = $request->input('position_x');
            $item->pos_y = $request->input('position_y');
            $item->save();

            return redirect()->route('admin.product.show',['item' => $item->product_id]);

        }catch(\Exception $ex){
//            dd($ex);
            error_log($ex);
            return back()->withErrors("Something Went Wrong")->withInput();
        }
    }

    public function edit(Product $item)
    {
        $categories = Category::all();
        $mainImage = ProductImage::where('product_id', $item->id)->where('is_main_image', 1)->first();
        $thumbnailImage = ProductImage::where('product_id', $item->id)->where('is_thumbnail', 1)->first();
        $detailImage = ProductImage::where('product_id', $item->id)
            ->where('is_main_image', 0)
            ->where('is_thumbnail', 0)->get();
        $selectedCategory = CategoryProduct::where('product_id', $item->id)->first();
        $data = [
            'product'    => $item,
            'categories'    => $categories,
            'selectedCategory'    => $selectedCategory,
            'mainImage'    => $mainImage,
            'thumbnailImage'    => $thumbnailImage,
            'detailImage'    => $detailImage,
        ];
        return view('admin.product.edit')->with($data);
    }

    public function update(Request $request){
//        dd($request);
        try{
            $validator = Validator::make($request->all(), [
                'name'        => 'required',
                'sku'         => 'required',
                'category'             => 'required',
                'price'             => 'required',
                'qty'             => 'required',
                'weight'             => 'required',
                'description'             => 'required',
            ]);
            $product = Product::find($request->input('id'));
            if($product->qty > $request->input('qty')){
                $prevQty = $product->qty - $request->input('qty');
                $prevQty = '-' . $prevQty;
            }
            else if($product->qty < $request->input('qty')){
                $prevQty = $request->input('qty') - $product->qty;
            }
            else if($request->input('qty') == 0){
                $prevQty = '-' . $product->qty;
            }
            else{
                $prevQty = 0;
            }

            if ($request->input('category') == "-1") {
                return back()->withErrors("Category is required")->withInput($request->all());
            }
//            dd($request);
            $detailImages = $request->file('detail_image');
            $mainImages = $request->file('main_image');
            $thumbnailImages = $request->file('thumbnail_image');

            if ($validator->fails())
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

            $dateTimeNow = Carbon::now('Asia/Jakarta');
            $slug = Utilities::CreateProductSlug($request->input('name'));

            $colourNew = Utilities::CreateProductSlug($request->input('colour'));

            if($request->input('is_customize') == 'on'){
                $customize = 1;
            }
            else{
                $customize = 0;
            }
//            dd($slug);
            // update product
            $product->is_customize = $customize;
            $product->category_id = $request->input('category');
            $product->name = $request->input('name');
            $product->slug = $slug."--".$colourNew;
            $product->sku = $request->input('sku');
            $product->description = $request->input('description');
            $product->style_notes = $request->input('style_notes');
            $product->qty = $request->input('qty');
            $product->price = (double) $request->input('price');
            $product->weight = $request->input('weight');
            $product->width = $request->input('width');
            $product->height = $request->input('height');
            $product->length = $request->input('length');
            $product->tag = $request->input('tags');
            $product->updated_at = $dateTimeNow->toDateTimeString();

            $product->save();

//            // update product category
//            $selectedCategory = CategoryProduct::where('product_id', $product->id)->first();
//            $selectedCategory->category_id = $request->input('category');
//            $selectedCategory->updated_at = $dateTimeNow->toDateTimeString();
//            $selectedCategory->save();


            // update product main image, thumbnail and image detail

            if(!empty($mainImages)){
                $mainImage = ProductImage::where('product_id', $product->id)->where('is_main_image', 1)->first();
//                dd($mainImage);
                if(!empty($mainImage)){

                    $mainImage->delete();

                    $img = Image::make($mainImages);
                    $extStr = $img->mime();
                    $ext = explode('/', $extStr, 2);
                    $filename = $product->id.'_main_'.$slug.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

                    if(env('SERVER_HOST_URL') == 'http://localhost:8000/'){
                        $img->save(public_path('storage/products/'. $filename), 75);
                    }
                    else{
                        $img->save('../public_html/storage/products/'. $filename, 75);
                    }

                    $newProductImage = ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $filename,
                        'is_main_image' => 1,
                        'is_thumbnail' => 0,
                    ]);
                }
            }
            if(!empty($thumbnailImages)){
                $thumbnailImage = ProductImage::where('product_id', $product->id)->where('is_thumbnail', 1)->first();
//                dd($thumbnailImage);
                if(!empty($thumbnailImage)){

                    $thumbnailImage->delete();

                    $img = Image::make($thumbnailImages);
                    $extStr = $img->mime();
                    $ext = explode('/', $extStr, 2);
                    $filename = $product->id.'_thumbnail_'.$slug.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

                    if(env('SERVER_HOST_URL') == 'http://localhost:8000/'){
                        $img->save(public_path('storage/products/'. $filename), 75);
                    }
                    else{
                        $img->save('../public_html/storage/products/'. $filename, 75);
                    }

                    $newProductImage = ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $filename,
                        'is_main_image' => 0,
                        'is_thumbnail' => 1,
                    ]);
                }
            }
            if(!empty($detailImages)){
//                dd($detailImages);
                $detailImage = ProductImage::where('product_id', $product->id)->where('is_main_image', 0)->where('is_thumbnail', 0)->get();

                foreach($detailImage as $image){
                    $image->delete();
                }

                for($i=0;$i<sizeof($detailImages);$i++){
                    $img = Image::make($detailImages[$i]);
                    $extStr = $img->mime();
                    $ext = explode('/', $extStr, 2);

                    $filename = $product->id.'_'.$i.'_'.$slug.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

                    if(env('SERVER_HOST_URL') == 'http://localhost:8000/'){
                        $img->save(public_path('storage/products/'. $filename), 75);
                    }
                    else{
                        $img->save('../public_html/storage/products/'. $filename, 75);
                    }

                    $newProductImage = ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $filename,
                        'is_main_image' => 0,
                        'is_thumbnail' => 0,
                    ]);
                }
            }

            // Update ZOHO Product
            if($prevQty != 0){
                $tmp = Zoho::stockAdjustment($product, $prevQty);
                //dd($tmp);
            }

            return redirect()->route('admin.product.show',['item' => $product->id]);

        }catch(\Exception $ex){
//            dd($ex);
            error_log($ex);
            return back()->withErrors("Something Went Wrong")->withInput();
        }
    }

    public function getProducts(Request $request){
        $term = trim($request->q);
        $roles = Product::where('id', '!=', $request->id)
            ->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', '%' . $term . '%');
            })
            ->get();

        $formatted_tags = [];

        foreach ($roles as $role) {
            $formatted_tags[] = ['id' => $role->id, 'text' => $role->name];
        }

        return \Response::json($formatted_tags);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $product = Product::find($request->id);
            $product->status = 2;
            $product->save();

            Session::flash('success', 'Success Deleting ');
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }


    }
}
