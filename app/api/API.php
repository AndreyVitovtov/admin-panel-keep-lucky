<?php

namespace App\API;

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
		if ($online) $params['online'] = 'true';
		return $this->get('admin/users', $params);
	}

	/**
	 * @throws Exception
	 */
	public function getUser($id): array
	{
		return $this->get('admin/users', $id);
	}

	/**
	 * @throws Exception
	 */
	public function getUsersStats(string $country = '', string $referral_code = '', string $dateFilter = ''): array
	{
		if (!empty($dateFilter)) {
			$allowedFilters = ['DAILY', 'MONTHLY', 'YEARLY'];
			if (!in_array($dateFilter, $allowedFilters)) {
				throw new InvalidArgumentException("Invalid date_filter: $dateFilter. Allowed: " . implode(', ', $allowedFilters));
			}
		}

		$params = [];
		if (!empty($country)) $params['country'] = $country;
		if (!empty($referral_code)) $params['referral_code'] = $referral_code;
		if (!empty($dateFilter)) $params['date_filter'] = $dateFilter;
		return $this->get('admin/users-stats', $params);
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
		return $this->get('admin/user-stats', $userId);
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
	public function getTrafficStats(string $country = '', string $region = '', string $city = '', string $referralCode = '', string $dateFilter = ''): array
	{
		if (!empty($dateFilter)) {
			$allowedFilters = ['DAILY', 'MONTHLY', 'YEARLY'];
			if (!in_array($dateFilter, $allowedFilters)) {
				throw new InvalidArgumentException("Invalid date_filter: $dateFilter. Allowed: " . implode(', ', $allowedFilters));
			}
		}
		$params = [];
		if (!empty($country)) $params['country'] = $country;
		if (!empty($region)) $params['region'] = $region;
		if (!empty($city)) $params['city'] = $city;
		if (!empty($referralCode)) $params['referral_code'] = $referralCode;
		return $this->get('admin/traffic-stats', $params);
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
	protected function get(string $endpoint, array|string $params = [], array $headers = []): array
	{
		return $this->makeRequest('GET', $endpoint, $params, $headers);
	}

	/**
	 * @throws Exception
	 */
	protected function post(string $endpoint, array $params = [], array $headers = []): array
	{
		return $this->makeRequest('POST', $endpoint, $params, $headers);
	}

	/**
	 * @throws Exception
	 */
	protected function patch(string $endpoint, array $params = [], array $headers = []): array
	{
		return $this->makeRequest('PATCH', $endpoint, $params, $headers);
	}

	/**
	 * @throws Exception
	 */
	private function makeRequest(string $method, string $endpoint, array|string $params = [], array $headers = []): array
	{
		$url = $_SESSION['application']['url'] . '/' . $endpoint;

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
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
				$headers[] = 'Content-Type: application/json';
				break;

			default:
				throw new Exception("Unsupported HTTP method: $method");
		}

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

	/**
	 * @throws Exception
	 */
	private function getAuthorizationBasic(): string
	{
		if (!empty($_SESSION['application']['username']) && !empty($_SESSION['application']['password'])) {
			return base64_encode($_SESSION['application']['username'] . ':' . $_SESSION['application']['password']);
		}
		throw new Exception('Application not selected');
	}
}