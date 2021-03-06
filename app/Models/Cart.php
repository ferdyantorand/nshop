<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 28 Dec 2018 04:12:15 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cart
 * 
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property string $voucher_code
 * @property string $description
 * @property int $qty
 * @property float $price
 * @property float $total_price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\Product $product
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Cart extends Eloquent
{
	protected $casts = [
		'user_id' => 'int',
		'product_id' => 'int',
		'qty' => 'int',
		'price' => 'float',
		'total_price' => 'float'
	];

	protected $fillable = [
		'user_id',
		'product_id',
		'voucher_code',
		'description',
		'qty',
		'price',
		'total_price'
	];

	protected $appends = [
	    'price_string',
        'total_price_string'
    ];

    public function getPriceStringAttribute(){
        return number_format($this->attributes['price'], 0, ",", ".");
    }

    public function getTotalPriceStringAttribute(){
        return number_format($this->attributes['total_price'], 0, ",", ".");
    }

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
