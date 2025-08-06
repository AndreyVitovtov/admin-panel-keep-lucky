<?php

namespace App\Controllers;

use App\API\API;
use App\Routes\Route;

class Test extends Controller
{
	/**
	 * @throws \Exception
	 */
	public function index()
	{
		$api = new API();
		echo json_encode($api->getAdminById(15));
//		echo json_encode($api->createAdmin('TestAdmin', 'testadmin2', 'ADMIN', ['apk1'], ['shop1'], ['referral1']));
//		echo json_encode($api->deleteAdmin(11));
//		echo json_encode($api->getAdminAccessShop());
//		echo json_encode($api->getTrafficStats('', '', '', '', '2025-01-01', '2025-08-03', 'DAY'));
	}
}
//admin/users-stats - этот метод не плохо бы вернуть, так как уже работал в админке
//{
//	"message": "Cannot GET /admin/users-stats",
//	"error": "Not Found",
//	"statusCode": 404
//}

//
//admin/users-stats/4M1wRJwkQ8
//{
//	"total_users": 0,
//	"total_online_users": 0
//}
//
//admin/users-stats/4M1wRJwkQ8?country=France
//{
//	"statusCode": 500,
//	"message": "Internal server error"
//}
//
//admin/filters
//{
//	"country": [],
//	"region": [],
//	"city": [],
//	"zip": [],
//	"isp": [],
//	"connectionType": []
//}