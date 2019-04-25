<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 07 Dec 2018 08:44:36 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $image_path
 * @property string $company_name
 * @property string $email_token
 * @property string $phone
 * @property int $status_id
 * @property string $zoho_id
 * @property string $zoho_primary_contact_id
 * @property string $tax_no
 * @property \Carbon\Carbon $email_verified_at
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $addresses
 * @property \Illuminate\Database\Eloquent\Collection $orders
 * @property \Illuminate\Database\Eloquent\Collection $product_reviews
 * @property \Illuminate\Database\Eloquent\Collection $user_user_groups
 * @property \Illuminate\Database\Eloquent\Collection $wishlists
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;
	protected $casts = [
		'status_id' => 'int'
	];

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'email_token',
		'remember_token'
	];

	protected $fillable = [
		'first_name',
        'last_name',
		'email',
		'password',
		'image_path',
		'company_name',
		'email_token',
		'phone',
		'status_id',
        'zoho_id',
        'zoho_primary_contact_id',
		'tax_no',
		'email_verified_at',
		'remember_token'
	];

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function addresses()
	{
		return $this->hasMany(\App\Models\Address::class);
	}

	public function orders()
	{
		return $this->hasMany(\App\Models\Order::class);
	}

	public function product_reviews()
	{
		return $this->hasMany(\App\Models\ProductReview::class);
	}

	public function user_user_groups()
	{
		return $this->hasMany(\App\Models\UserUserGroup::class);
	}

	public function wishlists()
	{
		return $this->hasMany(\App\Models\Wishlist::class);
	}
}
