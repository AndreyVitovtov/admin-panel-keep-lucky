<?php

namespace App\API;

use App\Models\AdminModel;
use Exception;
use InvalidArgumentException;

class API
{
	/**
	 * @throws Exception
	 */
	public function getUsers(int $skip = 0, int $take = 100, bool $online = false): array
	{
		$params = [
			'skip' => $skip,
			'take' => $take
		];

		$shops = implode(',', $_SESSION['shops'] ?? []);
		$apks = implode(',', $_SESSION['apks'] ?? []);
		$referralCode = $_SESSION['referralCode'] ?? '';
		if (!empty($shops)) $params = array_merge($params, ['shop' => $shops]);
		if (!empty($apks)) $params = array_merge($params, ['apk' => $apks]);
		if (!empty($referralCode)) $params = array_merge($params, ['referral_code' => $referralCode]);

		if ($online) $params['online'] = 'true';
		return $this->get('admin/search/users', $params);
	}

	/**
	 * @throws Exception
	 */
	public function getUser($id): array
	{
		return $this->get('admin/search/users', [
			'user_id' => $id
		]);
	}

	/**
	 * @throws Exception
	 */
	public function getUsersStats(string $country = '', string $region = '', string $city = ''): array
	{
		$params = [];
		if (!empty($country)) $params['country'] = $country;
		if (!empty($region)) $params['region'] = $region;
		if (!empty($city)) $params['city'] = $city;

		$shops = implode(',', $_SESSION['shops'] ?? []);
		$apks = implode(',', $_SESSION['apks'] ?? []);
		if (!empty($shops)) $params = array_merge($params, ['shop' => $shops]);
		if (!empty($apks)) $params = array_merge($params, ['apk' => $apks]);

		return $this->get('admin/stats/users', $params);
	}

	/**
	 * @throws Exception
	 */
	public function getUsersByReferralCode(string $referralCode): array
	{
		return $this->get('admin/users-by-referral-code', $referralCode);
	}

	/**
	 * @throws Exception
	 */
	public function getOnlineUsersByReferralCode(string $referralCode): array
	{
		return $this->get('admin/online-users-by-referral-code', $referralCode);
	}

	/**
	 * @throws Exception
	 */
	public function getUsersByLogin(string $login): array
	{
		return $this->get('admin/users-by-login', $login);
	}

	/**
	 * @throws Exception
	 */
	public function getUserStats(int $userId): array
	{
		$params = [];
		$shops = implode(',', $_SESSION['shops'] ?? []);
		$apks = implode(',', $_SESSION['apks'] ?? []);
		if (!empty($shops)) $params = array_merge($params, ['shop' => $shops]);
		if (!empty($apks)) $params = array_merge($params, ['apk' => $apks]);

		return $this->get('admin/stats/users', $userId, $params);
	}

	/**
	 * @throws Exception
	 */
	public function writeOffBalance(int $id, int|float $amount = 0): array
	{
		return $this->post("admin/write-off-balance/$id", [
			'amount' => $amount
		]);
	}

	/**
	 * @throws Exception
	 */
	public function addBalance(int $id, int|float $amount = 0): array
	{
		return $this->post("admin/add-balance/$id", [
			'amount' => $amount
		]);
	}

	/**
	 * @throws Exception
	 */
	public function blockUser(int $id): array
	{
		return $this->post("admin/block-user/$id");
	}

	/**
	 * @throws Exception
	 */
	public function unblockUser(int $id): array
	{
		return $this->post("admin/unblock-user/$id");
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficStats(string $country = '', string $region = '', string $city = '', string $referralCode = '',
	                                string $dateFrom = '', string $dateTo = '', string $sortedBy = null): array
	{
		$params = [];
		if (!empty($country)) $params['country'] = $country;
		if (!empty($region)) $params['region'] = $region;
		if (!empty($city)) $params['city'] = $city;
		if (!empty($referralCode)) $params['referral_code'] = $referralCode;
		if (!empty($dateFrom)) $params['date_from'] = $dateFrom . ' 00:00:00';
		if (!empty($dateTo)) $params['date_to'] = $dateTo . ' 23:59:59';
		if (!empty($sortedBy) && $sortedBy != 'SUMMARY') $params['group_by'] = $sortedBy;

		$shops = implode(',', $_SESSION['shops'] ?? []);
		$apks = implode(',', $_SESSION['apks'] ?? []);
		if (!empty($shops)) $params = array_merge($params, ['shop' => $shops]);
		if (!empty($apks)) $params = array_merge($params, ['apk' => $apks]);
		return $this->get('admin/stats/traffic', $params);
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficStatsTotalConnections(): array
	{
		return $this->get('admin/traffic-stats/total-connections');
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficStatsTotalSuccessfulConnections(): array
	{
		return $this->get('admin/traffic-stats/total-successful-connections');
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficStatsByCountry(string $country): array
	{
		return $this->get('admin/traffic-stats/by-country', [
			'country' => $country
		]);
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficStatsByRegion(string $region): array
	{
		return $this->get('admin/traffic-stats/by-region', [
			'region' => $region
		]);
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficStatsByCity(string $city): array
	{
		return $this->get('admin/traffic-stats/by-city', [
			'city' => $city
		]);
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficLoader(string $referralCode): array
	{
		return $this->get('admin/traffic-loader/users', $referralCode);
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficLoaderOnline(string $referralCode): array
	{
		return $this->get('admin/traffic-loader/users/online', $referralCode);
	}

	/**
	 * @throws Exception
	 */
	public function getTrafficLoaderLocation(string $location): array
	{
		return $this->get('admin/traffic-loader/users/location', $location);
	}

	/**
	 * @throws Exception
	 */
	public function getProxyStats(): array
	{
		return $this->get('proxy/stats');
	}

	/**
	 * @throws Exception
	 */
	public function filters(string $country = '', string $region = '', string $city = ''): array
	{
		$params = [];
		$shops = implode(',', $_SESSION['shops'] ?? []);
		$apks = implode(',', $_SESSION['apks'] ?? []);
		$referralCode = $_SESSION['referralCode'] ?? '';
		$dateFrom = $_SESSION['dateFrom'] ?? '';
		$dateTo = $_SESSION['dateTo'] ?? '';

		if (!empty($referralCode)) $params['referral_code'] = $referralCode;
		if (!empty($shops)) $params['shops'] = $shops;
		if (!empty($apks)) $params['apks'] = $apks;
		if (!empty($dateFrom)) $params['date_from'] = $dateFrom;
		if (!empty($dateTo)) $params['date_to'] = $dateTo;
		if (!empty($country)) $params['country'] = $country;
		if (!empty($region)) $params['region'] = $region;
		if (!empty($city)) $params['city'] = $city;

		return $this->get('admin/search/filters', $params);
	}

	/**
	 * @throws Exception
	 */
	public function ipAddresses(string $country = '', string $region = '', string $city = '', bool $includeAll = true): array
	{
		$params = [];
		$shops = implode(',', $_SESSION['shops'] ?? []);
		$apks = implode(',', $_SESSION['apks'] ?? []);
		$referralCode = $_SESSION['referralCode'] ?? '';
		$dateFrom = $_SESSION['dateFrom'] ?? '';
		$dateTo = $_SESSION['dateTo'] ?? '';

		if (!empty($referralCode)) $params['referral_code'] = $referralCode;
		if (!empty($shops)) $params['shops'] = $shops;
		if (!empty($apks)) $params['apks'] = $apks;
		if (!empty($dateFrom)) $params['date_from'] = $dateFrom;
		if (!empty($dateTo)) $params['date_to'] = $dateTo;
		if (!empty($country)) $params['country'] = $country;
		if (!empty($region)) $params['region'] = $region;
		if (!empty($city)) $params['city'] = $city;

		$params['include_all'] = $includeAll;

		return $this->get('admin/search/ipAddresses', $params);
	}

	/**
	 * @throws Exception
	 */
	public function createAdmin(string $login, string $password, string $role, array $apks, array $shops, array $referralCodes): array
	{
		return $this->post('admin/roles', json_encode([
			'login' => $login,
			'password' => $password,
			'role' => $role,
			'apks' => $apks,
			'shops' => $shops,
			'referral_codes' => $referralCodes
		]));
	}

	/**
	 * @throws Exception
	 */
	public function getAdmins(): array
	{
		return $this->get('admin/roles');
	}

	/**
	 * @throws Exception
	 */
	public function updateAdmin(int $id, array $apks, array $shops, array $referralCodes, string $login = '', string $password = ''): array
	{
		$params = [
			'apks' => $apks,
			'shops' => $shops,
			'referral_codes' => $referralCodes
		];

		if (!empty($login)) {
			$params['login'] = $login;
		}

		if (!empty($password)) {
			$params['password'] = $password;
		}

		return $this->patch("admin/roles/$id", json_encode($params));
	}

	/**
	 * @throws Exception
	 */
	public function updateAdminLoginPassword(int $id, string $login = '', string $password = ''): ?array
	{
		if (!empty($login)) $params['login'] = $login;
		if (!empty($password)) $params['password'] = $password;
		if (isset($params)) {
			return $this->patch("admin/roles/$id", json_encode($params));
		}
		return null;
	}

	/**
	 * @throws Exception
	 */
	public function deleteAdmin(int $id): array
	{
		return $this->delete("admin/roles/$id");
	}

	/**
	 * @throws Exception
	 */
	public function getAdminAccess(): array
	{
		return $this->get('admin/roles/access/current');
	}

	/**
	 * @throws Exception
	 */
	public function getAdminAccessApk(): array
	{
		return $this->get('admin/roles/access/apk');
	}

	/**
	 * @throws Exception
	 */
	public function getAdminAccessShop(): array
	{
		return $this->get('admin/roles/access/shop');
	}

	/**
	 * @throws Exception
	 */
	public function getAdminById(int $id): array
	{
		return $this->get("admin/roles/$id");
	}

	/**
	 * @throws Exception
	 */
	protected function get(string $endpoint, array|string $params = [], array $headers = []): array
	{
		return $this->makeRequest('GET', $endpoint, $params, $headers);
	}

	/**
	 * @throws Exception
	 */
	protected function post(string $endpoint, array|string $params = [], array $headers = []): array
	{
		return $this->makeRequest('POST', $endpoint, $params, $headers);
	}

	/**
	 * @param string $dateFilter
	 * @param string $country
	 * @return array
	 */
	public function getArr(string $dateFilter, string $country): array
	{
		if (!empty($dateFilter)) {
			$allowedFilters = ['DAILY', 'MONTHLY', 'YEARLY'];
			if (!in_array($dateFilter, $allowedFilters)) {
				throw new InvalidArgumentException("Invalid date_filter: $dateFilter. Allowed: " . implode(', ', $allowedFilters));
			}
		}
		$params = [];
		if (!empty($country)) $params['country'] = $country;
		if (!empty($dateFilter)) $params['date_filter'] = $dateFilter;
		return $params;
	}

	/**
	 * @throws Exception
	 */
	protected function patch(string $endpoint, array|string $params = [], array $headers = []): array
	{
		return $this->makeRequest('PATCH', $endpoint, $params, $headers);
	}

	/**
	 * @throws Exception
	 */
	protected function put(string $endpoint, array|string $params = [], array $headers = []): array
	{
		return $this->makeRequest('PUT', $endpoint, $params, $headers);
	}

	/**
	 * @throws Exception
	 */
	protected function delete(string $endpoint, array|string $params = [], array $headers = []): array
	{
		return $this->makeRequest('DELETE', $endpoint, $params, $headers);
	}

	/**
	 * @throws Exception
	 */
	private function makeRequest(string $method, string $endpoint, array|string $params = [], array $headers = []): array
	{
		$url = BASE_URL_API . '/' . $endpoint;

		$ch = curl_init();

		$method = strtoupper($method);

		switch ($method) {
			case 'GET':
				if (!empty($params)) {
					if (is_string($params)) $url .= '/' . $params;
					if (is_array($params)) $url .= '?' . http_build_query($params);
				}
				break;

			case 'POST':
			case 'PATCH':
			case 'PUT':
			case 'DELETE':
				if (is_string($params)) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				} else {
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
				}
				$headers[] = 'Content-Type: application/json';
				break;

			default:
				throw new Exception("Unsupported HTTP method: $method");
		}

//		if($endpoint == 'admin/search/filters') {
//			dd($params);
//		}

//		if($endpoint == 'admin/stats/traffic') {
//			dd($params);
//		}

		$headers[] = 'Authorization: Basic ' . $this->getAuthorizationBasic();

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HTTPHEADER => $headers,
		]);

		$response = curl_exec($ch);
		$error = curl_error($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if ($error) {
			throw new Exception("cURL error: $error");
		}

		return [
			'status' => $status,
			'response' => json_decode($response, true),
		];
	}

	private function setAuthorizationBasic(string $username, string $password): void
	{
		$_SESSION['application']['username'] = $username;
		$_SESSION['application']['password'] = $password;
	}

	/**
	 * @throws Exception
	 */
	private function getAuthorizationBasic(): string
	{
		if (!empty($_SESSION['id'])) {
			$admin = (new AdminModel())->find($_SESSION['id']);
			$username = $admin->login;
			$password = $admin->password;

			if (!empty($username) && !empty($password)) {
				return base64_encode($username . ':' . decryptData($password, CIPHER));
			}
		}
		throw new Exception('Application not selected');
	}
}