<?php

namespace App\Models;

/**
 * @property mixed|null $login
 * @property mixed|null $name
 * @property mixed|null $role
 * @property mixed|null $avatar
 * @property mixed|null $referral_code
 * @property mixed|string|null $password
 * @property mixed|null $admin_id
 */
class AdminModel extends Model
{
	protected $table = 'admins';
	protected $fields = [
		'name', 'login', 'password', 'avatar', 'role', 'referral_code'
	];
}