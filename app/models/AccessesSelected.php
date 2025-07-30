<?php

namespace App\Models;

use App\Models\Model;

/**
 * @property mixed|null $admin_id
 * @property mixed|null $access_id
 * @property int|mixed|null $available
 */
class AccessesSelected extends Model
{
	/**
	 * @var mixed|null
	 */
	protected $table = 'accesses_selected';
	protected $fields = [
		'admin_id', 'access_id', 'available'
	];
}