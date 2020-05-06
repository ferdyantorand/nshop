<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 06 Dec 2018 06:52:28 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Configuration
 *
 * @property int $id
 * @property string $configuration_key
 * @property string $configuration_value
 * @property string $configuration_value2
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Configuration extends Eloquent
{
	protected $fillable = [
		'configuration_key',
		'configuration_value',
        'configuration_value2'
	];
}
