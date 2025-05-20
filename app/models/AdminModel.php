<?php

namespace App\Models;

/**
 * @property mixed|null $login
 * @property mixed|null $name
 * @property mixed|null $role
 * @property mixed|null $avatar
 * @property mixed|null $referral_code
 */
class AdminModel extends Model
{
	protected $table = 'admins';
	protected $fields = [
		'name', 'login', 'password', 'avatar', 'role', 'referral_code'
	];
}