<?php

namespace App\Controllers;

use App\Utility\Request;

class Api extends Controller
{
	public function updateShops(Request $request): void
	{
		$shops = $request->get('shops') ?? [];
		$_SESSION['shops'] = $shops;

		try {
			echo $this->getDataForDashboard();
		} catch (\Exception $e) {
			echo json_encode([
				'error' => $e->getMessage()
			]);
		}
	}

	public function updateApk(Request $request): void
	{
		$apk = $request->get('apk') ?? [];
		$_SESSION['apk'] = $apk;

		try {
			echo $this->getDataForDashboard();
		} catch (\Exception $e) {
			echo json_encode([
				'error' => $e->getMessage()
			]);
		}
	}

	public function updateReferralCode(Request $request): void
	{
		$referralCode = $request->get('referralCode') ?? '';
		$_SESSION['referralCode'] = $referralCode;

		try {
			echo $this->getDataForDashboard();
		} catch (\Exception $e) {
			echo json_encode([
				'error' => $e->getMessage()
			]);
		}
	}

	public function getDataForUpdateDashboard(): void
	{
		try {
			echo $this->getDataForDashboard();
		} catch (\Exception $e) {
			echo json_encode([
				'error' => $e->getMessage()
			]);
		}
	}

	public function updateLocation(Request $request): void
	{
		$country = $request->get('country') ?? '';
		$region = $request->get('region') ?? '';
		$city = $request->get('city') ?? '';
		$referralCode = $request->get('referralCode') ?? '';
		$dateFrom = $request->get('dateFrom') ?? '';
		$dateTo = $request->get('dateTo') ?? '';

		$_SESSION['country'] = $country;
		$_SESSION['region'] = $region;
		$_SESSION['city'] = $city;
		$_SESSION['referralCode'] = $referralCode;
		$_SESSION['dateFrom'] = $dateFrom;
		$_SESSION['dateTo'] = $dateTo;

		try {
			echo $this->getDataForDashboard();
		} catch (\Exception $e) {
			echo json_encode([
				'error' => $e->getMessage()
			]);
		}
	}

	public function updateDate(Request $request): void
	{
		$dateFrom = $request->get('dateFrom') ?? '';
		$dateTo = $request->get('dateTo') ?? '';

		$_SESSION['dateFrom'] = $dateFrom;
		$_SESSION['dateTo'] = $dateTo;

		try {
			echo $this->getDataForDashboard();
		} catch (\Exception $e) {
			echo json_encode([
				'error' => $e->getMessage()
			]);
		}
	}

	/**
	 * @throws \Exception
	 */
	public function getDataForDashboard(bool $array = false, $data = []): false|string|array
	{
		if (!empty($_SESSION['country'])) $country = trim(rtrim($_SESSION['country'], "\"'"));
		if (!empty($_SESSION['region'])) $region = trim(rtrim($_SESSION['region'], "\"'"));
		if (!empty($_SESSION['city'])) $city = trim(rtrim($_SESSION['city'], "\"'"));
		if (!empty($_SESSION['referralCode'])) $referralCode = trim(rtrim($_SESSION['referralCode'], "\"'"));
		if (!empty($_SESSION['dateFrom'])) $dateFrom = trim(rtrim($_SESSION['dateFrom'], "\"'"));
		if (!empty($_SESSION['dateTo'])) $dateTo = trim(rtrim($_SESSION['dateTo'], "\"'"));

		$api = new \App\API\API();
		$trafficStats = $api->getTrafficStats($country ?? '', $region ?? '', $city ?? '', $referralCode ?? '', $dateFrom ?? '', $dateTo ?? '');
		$usersStats = $api->getUsersStats($country ?? '', $region ?? '', $city ?? '');

		$data = [];

		if ($trafficStats['status'] == 200) {
			$data = array_merge($data, $trafficStats['response']);
		}

		if ($usersStats['status'] == 200) {
			$data = array_merge($data, $usersStats['response']);
		}

		return ((!$array) ? json_encode($data) : $data);
	}

	/**
	 * @throws \Exception
	 */
	public function getShopsAndApk(Request $request): void
	{
		$adminId = $request->get('adminId') ?? 0;

		$api = new \App\API\API();

		$apks = $api->getAdminAccessApk();
		if ($apks['status'] == 200) $apks = $apks['response'];
		else $apks = [];

		$shops = $api->getAdminAccessShop();
		if ($shops['status'] == 200) $shops = $shops['response'];
		else $shops = [];

		// TODO: Get selected APK and Shops $selectedShops, $selectedApk
		//  get admin by id

		echo json_encode([
			'shops' => $shops,
			'apk' => $apks,
			'selectedShops' => $selectedShops ?? [],
			'selectedApk' => $selectedApk ?? [],
			'adminId' => $adminId ?? ''
		]);
	}
}