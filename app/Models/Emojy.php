<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 08 Aug 2019 04:50:38 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Emojy
 * 
 * @property int $id
 * @property string $path
 *
 * @package App\Models
 */
class Emojy extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'path'
	];
}
