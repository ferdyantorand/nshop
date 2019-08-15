<?php
/**
 * Created by PhpStorm.
 * User: YANSEN
 * Date: 12/10/2018
 * Time: 10:03
 */

namespace App\Transformer;


use App\Models\Product;
use App\Models\Status;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Log;

class ProductTransformer extends TransformerAbstract
{

    public function transform(Product $product){

        try{
            $createdDate = Carbon::parse($product->created_at)->format('d M Y');
            $updatedDate = Carbon::parse($product->updated_at)->format('d M Y');

            $itemShowUrl = route('admin.product.show', ['item' => $product->id]);
            $itemEditUrl = route('admin.product.edit', ['item' => $product->id]);
            $itemCopyUrl = route('admin.product.create-copy', ['item' => $product->id]);

            $status = Status::find($product->status);
            $action = "<a class='btn btn-xs btn-primary' href='".$itemShowUrl."' data-toggle='tooltip' data-placement='top'><i class='icon-details'></i></a> ";
            $action .= "<a class='btn btn-xs btn-info' href='".$itemCopyUrl."' data-toggle='tooltip' data-placement='top'><i class='icon-copy'></i></a> ";
            $action .= "<a class='btn btn-xs btn-info' href='".$itemEditUrl."' data-toggle='tooltip' data-placement='top'><i class='icon-pencil'></i></a> ";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $product->id ."' ><i class='icon-remove'></i></a>";


            return[
                'name'              => $product->name,
                'sku'               => $product->sku,
                'color'             => ucfirst($product->colour),
                'qty'               => $product->qty,
                'price'             => $product->price_string,
                'created_at'        => $createdDate,
                'update_at'         => $updatedDate,
                'status'            => $status->description,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
            Log::error("ProductTransformer.php > transform ".$exception);
        }
    }
}
