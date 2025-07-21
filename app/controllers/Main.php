<?php

namespace App\Controllers;

use App\API\API;
use App\Models\Apk;
use App\Models\Shop;
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
			$countries = array_keys($filters['response']['country']);
			$regions = array_keys($filters['response']['region']);
			$cities = array_keys($filters['response']['city']);
		}


		$apks = (new Apk())->getObjects();
		$shops = (new Shop())->getObjects();

		$selectedShops = $_SESSION['shops'] ?? [];
		$selectedApks = $_SESSION['apk'] ?? [];
		$referralCode = $_SESSION['referralCode'] ?? '';
		$selectedCountry = $_SESSION['country'] ?? '';
		$selectedRegion = $_SESSION['region'] ?? '';
		$selectedCity = $_SESSION['city'] ?? '';
		$dateFrom = $_SESSION['dateFrom'] ?? '';
		$dateTo = $_SESSION['dateTo'] ?? '';

		$dataForDashboard = (new \App\Controllers\Api())->getDataForDashboard(true);

		$this->view('dashboard', [
			'title' => __('traffic'),
			'pageTitle' => __('traffic'),
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
			'countries' => $countries ?? [],
			'regions' => $regions ?? [],
			'cities' => $cities ?? [],
			'shops' => $shops,
			'apks' => $apks,
			'selectedShops' => $selectedShops,
			'selectedApks' => $selectedApks,
			'referralCode' => $referralCode,
			'dataForDashboard' => $dataForDashboard,
			'selectedCountry' => $selectedCountry ?? '',
			'selectedRegion' => $selectedRegion ?? '',
			'selectedCity' => $selectedCity ?? '',
			'dateFrom' => $dateFrom ?? '',
			'dateTo' => $dateTo ?? ''
		]);
	}
}