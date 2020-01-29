<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Jan 2020 04:55:28 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OrderWa
 *
 * @property int $id
 * @property int $order_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address_description
 * @property string $address_street
 * @property int $address_province
 * @property int $address_city
 * @property string $address_postal_code
 * @property \Carbon\Carbon $shipping_date
 *
 *
 * @property \App\Models\City $city
 * @property \App\Models\Country $country
 * @property \App\Models\Province $province
 *
 * @package App\Models
 */
class OrderWa extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int',
		'address_province' => 'int',
		'address_city' => 'int'
	];

	protected $dates = [
		'shipping_date'
	];

	protected $fillable = [
		'order_id',
		'name',
		'email',
		'phone',
		'address_description',
		'address_street',
		'address_province',
		'address_city',
		'address_postal_code',
		'shipping_date'
	];

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class);
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    public function province()
    {
        return $this->belongsTo(\App\Models\Province::class);
    }
}
