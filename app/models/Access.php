<?php

namespace App\Models;

/**
 * @property mixed|null $admin_id
 * @property mixed|null $application_id
 * @property mixed|null $controller
 * @property mixed|null $method
 * @property int|mixed|null $available
 */
class Access extends Model
{
	protected $table = 'accesses';
	protected $fields = [
		'admin_id', 'application_id', 'controller', 'method', 'available'
	];
}