<?php

namespace App\Middlewares;

use App\Controllers\Errors;
use App\Models\Access;

class AccessControl extends Middleware
{

	public function handle(): void
	{
//		if (isAuth() && $_SESSION['role'] != 'superadmin') {
//			global $cName;
//			global $cMethod;
//			$access = (new Access())->get([
//				'application_id' => $_SESSION['application']['id'] ?? 0,
//				'admin_id' => $_SESSION['id'] ?? 0,
//				'controller' => $cName,
//				'method' => $cMethod
//			]);
//
//			if (empty($access)) {
//				(new Errors())->error403();
//			}
//		}
	}
}