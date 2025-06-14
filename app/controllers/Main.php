<?php

namespace App\Controllers;

use App\API\API;
use Exception;

class Main extends Controller
{
	public function __construct()
	{
//		$this->forbid = [
//			'guest' => 'ALL'
//		];
	}

	/**
	 * @throws Exception
	 */
	public function index(): void
	{
		$this->auth();
		$api = new API();

		if (getRole() == 'admin3') $refCode = $_SESSION['refCode'];

		$usersStats = $api->getUsersStats('', $refCode ?? '');
		if ($usersStats['status'] == 200) {
			$totalUsers = $usersStats['response']['total_users'];
			$onlineUsers = $usersStats['response']['total_online_users'];
		}

		$filters = $api->filters();
		if ($filters['status'] == 200) {
			$usersByCountries = $filters['response']['country'];
			$usersByRegions = $filters['response']['region'];
			$usersByCities = $filters['response']['city'];
		}

		$this->view('dashboard', [
			'title' => __('dashboard'),
			'pageTitle' => __('dashboard'),
			'assets' => [
				'js' => [
					'chart.umd.min.js',
					'chartjs-plugin-datalabels.min.js',
					'dashboard.js'
				]
			],
			'totalUsers' => $totalUsers ?? 0,
			'onlineUsers' => $onlineUsers ?? 0,
			'usersByCountries' => $usersByCountries ?? [],
			'usersByRegions' => $usersByRegions ?? [],
			'usersByCities' => $usersByCities ?? [],
		]);
	}
}