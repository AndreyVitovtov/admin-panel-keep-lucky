<?php

namespace App\Controllers;

use App\Utility\Request;

class Api extends Controller
{
	public function __construct()
	{
		if (empty($_SESSION['adminId']) && !empty($_SESSION['id'])) {
			$admin = new \App\Models\AdminModel();
			$admin->find($_SESSION['id']);
			$_SESSION['adminId'] = $admin->admin_id;
		}
	}

	/**
	 * @throws \Exception
	 */
	public function updateShops(Request $request): void
	{
		$shops = $request->get('shops') ?? [];
		$_SESSION['shops'] = $shops;

		$api = new \App\API\API();

		$res = $api->getAdminAccessApk();
		if ($res['status'] == 200) {
			$apks = $res['response'];
		}

		$referralCode = $_SESSION['referralCode'] ?? null;
		$referralCode = is_null($referralCode) ? [] : [$referralCode];

		$api->updateAdmin($_SESSION['adminId'], $apks ?? $_SESSION['apks'] ?? [], $shops, $referralCode);

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
	public function updateApk(Request $request): void
	{
		$apks = $request->get('apk') ?? [];
		$_SESSION['apks'] = $apks;

		$api = new \App\API\API();

		$res = $api->getAdminAccessShop();
		if ($res['status'] == 200) {
			$shops = $res['response'];
		}

		$referralCode = $_SESSION['referralCode'] ?? null;
		$referralCode = is_null($referralCode) ? [] : [$referralCode];

		$api->updateAdmin($_SESSION['adminId'], $apks, $shops ?? $_SESSION['shops'] ?? [], $referralCode);

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
	public function updateReferralCode(Request $request): void
	{
		$referralCode = $request->get('referralCode') ?? '';
		$_SESSION['referralCode'] = $referralCode;

		$api = new \App\API\API();

		$res = $api->getAdminAccessApk();
		if ($res['status'] == 200) {
			$apks = $res['response'];
		}

		$res = $api->getAdminAccessShop();
		if ($res['status'] == 200) {
			$shops = $res['response'];
		}

		$api->updateAdmin($_SESSION['adminId'], $apks ?? [], $shops ?? [], [$referralCode] ?? []);

		try {
			echo $this->getDataForDashboard();
		} catch (\Exception $e) {
			echo json_encode([
				'error' => $e->getMessage()
			]);
		}
	}

	public function updateSorted(Request $request): void
	{
		$_SESSION['trafficSortedBy'] = $request->get('sortedBy') ?? '';
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
		if (!empty($_SESSION['trafficSortedBy'])) $sortedBy = trim(rtrim($_SESSION['trafficSortedBy'], "\"'"));

		$api = new \App\API\API();
		$trafficStats = $api->getTrafficStats($country ?? '', $region ?? '', $city ?? '',
			$referralCode ?? '', $dateFrom ?? '', $dateTo ?? '', $sortedBy ?? '');
		$usersStats = $api->getUsersStats($country ?? '', $region ?? '', $city ?? '');
		if (($_SESSION['usersStatsOnline'] ?? false)) {
			$filters = $api->filters($country ?? '', $region ?? '', $city ?? '');
		} else {
			$filters = $api->ipAddresses($country ?? '', $region ?? '', $city ?? '');
		}

		$data = [];

		if ($trafficStats['status'] == 200) {
			$data['tableTrafficStats'] = html('Main/tableTrafficStats.php', $trafficStats);
		}

		if ($usersStats['status'] == 200) {
			$data = array_merge($data, $usersStats['response']);
		}

		if ($filters['status'] == 200) {
			$filters = $filters['response'];
			$data['usersByCountries'] = $filters['country'];
			$data['usersByRegions'] = $filters['region'];
			$data['usersByCities'] = $filters['city'];
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

		$resAdmin = $api->getAdminById($adminId);
		if ($resAdmin['status'] == 200) $admin = $resAdmin['response'];
		else $admin = [];
		$selectedShops = $admin['shops'] ?? [];
		$selectedApk = $admin['apks'] ?? [];
		$referralCode = $admin['referral_codes'][0] ?? '';

		echo json_encode([
			'shops' => $shops,
			'apk' => $apks,
			'selectedShops' => $selectedShops ?? [],
			'selectedApk' => $selectedApk ?? [],
			'referralCode' => $referralCode ?? '',
			'adminId' => $adminId ?? ''
		]);
	}

	/**
	 * @throws \Exception
	 */
	public function getRegionsCitiesByCountry(Request $request): void
	{
		$country = $request->get('country') ?? '';
		$region = $request->get('region') ?? '';

		$api = new \App\API\API();

		if (empty($country) && empty($region)) {
			$res = $api->filters();
		} else {
			if (!empty($region)) {
				$res = $api->filters('', $region);
			} else {
				$res = $api->filters($country);
			}
		}

		if ($res['status'] == 200) {
			echo json_encode([
				'regions' => array_keys($res['response']['region']),
				'cities' => array_keys($res['response']['city']),
				'country' => $country,
				'region' => $region
			]);
		} else {
			echo json_encode([
				'regions' => [],
				'cities' => []
			]);
		}
	}

	/**
	 * @throws \Exception
	 */
	public function getStatsUsers(Request $request): void
	{
		$online = $request->get('online') ?? '';
		$_SESSION['usersStatsOnline'] = ($online == 'true');
		if (!empty($_SESSION['country'])) $country = $_SESSION['country'];
		if (!empty($_SESSION['region'])) $region = $_SESSION['region'];
		if (!empty($_SESSION['city'])) $city = $_SESSION['city'];

		$api = new \App\API\API();

		if (($_SESSION['usersStatsOnline'] ?? false)) {
			$res = $api->filters($country ?? '', $region ?? '', $city ?? '');
		} else {
			$res = $api->ipAddresses($country ?? '', $region ?? '', $city ?? '');
		}

		if ($res['status'] == 200) {
			$res = $res['response'];
			$data['usersByCountries'] = $res['country'];
			$data['usersByRegions'] = $res['region'];
			$data['usersByCities'] = $res['city'];
			echo json_encode($data);
		} else {
			echo json_encode([
				'usersByCountries' => [],
				'usersByRegions' => [],
				'usersByCities' => []
			]);
		}
	}
}