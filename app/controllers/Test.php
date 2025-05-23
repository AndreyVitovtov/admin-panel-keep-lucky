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
		echo json_encode($api->getUser(2));
	}
}