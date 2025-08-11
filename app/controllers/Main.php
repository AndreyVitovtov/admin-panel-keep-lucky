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

		if (getRole() == 'admin') {
			$accesses = getAccesses();

			$adminAccess = $api->getAdminAccess();
			if ($adminAccess['status'] == 200) {
				$selectedShops = $adminAccess['response']['shops'];
				$_SESSION['shops'] = $selectedShops;

				$selectedApks = $adminAccess['response']['apks'];
				$_SESSION['apks'] = $selectedApks;

				$referralCode = $adminAccess['response']['referral_codes'][0] ?? '';
				$_SESSION['referralCode'] = $referralCode;
				//TODO: Referral codes array?
			}
		} else {
			$selectedShops = $_SESSION['shops'] ?? [];
			$selectedApks = $_SESSION['apks'] ?? [];
			$referralCode = $_SESSION['referralCode'] ?? '';
		}

		$usersStats = $api->getUsersStats($_SESSION['country'] ?? '', $_SESSION['region'] ?? '', $_SESSION['city'] ?? '');
		if ($usersStats['status'] == 200) {
			$totalUsers = $usersStats['response']['total_users'];
			$onlineUsers = $usersStats['response']['total_online_users'];
		}

		if (!empty($_SESSION['country'])) $country = $_SESSION['country'];
		if (!empty($_SESSION['region'])) $region = $_SESSION['region'];

		$filters = $api->filters();
		if ($filters['status'] == 200) {
			$countries = array_keys($filters['response']['country']);

			$filters = $api->filters($country ?? '');
			$regions = array_keys($filters['response']['region']);

			$filters = $api->filters($country ?? '', $region ?? '');
			$cities = array_keys($filters['response']['city']);

			$filters = $api->filters($country ?? '', $region ?? '');
			$usersByCountries = $filters['response']['country'];
			$usersByRegions = $filters['response']['region'];
			$usersByCities = $filters['response']['city'];
		}

		$apks = $api->getAdminAccessApk();
		if ($apks['status'] == 200) $apks = $apks['response'];
		else $apks = [];

		$shops = $api->getAdminAccessShop();
		if ($shops['status'] == 200) $shops = $shops['response'];
		else $shops = [];

		$selectedCountry = $_SESSION['country'] ?? '';
		$selectedRegion = $_SESSION['region'] ?? '';
		$selectedCity = $_SESSION['city'] ?? '';
		$dateFrom = $_SESSION['dateFrom'] ?? '';
		$dateTo = $_SESSION['dateTo'] ?? '';
		$trafficSortedBy = $_SESSION['trafficSortedBy'] ?? '';
		$referralCode = $_SESSION['referralCode'] ?? '';

		$dataForDashboard = (new \App\Controllers\Api())->getDataForDashboard(true);

		$api = new \App\API\API();
		$resTrafficStats = $api->getTrafficStats($selectedCountry ?? '', $selectedRegion ?? '', $selectedCity ?? '',
			$referralCode ?? '', $dateFrom ?? '', $dateTo ?? '', $trafficSortedBy ?? '');

		$this->view('dashboard', [
			'title' => __('traffic'),
			'pageTitle' => __('traffic'),
			'assets' => [
				'js' => [
					'chart.umd.min.js',
					'chartjs-plugin-datalabels.min.js',
					'dashboard.js'
				],
				'css' => [
					'loader.css'
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
			'dateTo' => $dateTo ?? '',
			'accesses' => $accesses ?? [],
			'trafficSortedBy' => $trafficSortedBy ?? '',
			'trafficStats' => $resTrafficStats
		]);
	}
}