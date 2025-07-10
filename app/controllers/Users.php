<?php

namespace App\Controllers;

use App\API\API;
use App\Utility\Request;
use Exception;

class Users extends Controller
{
	/**
	 * @throws Exception
	 */
	public function index($page = null, $online = null): void
	{
		$this->auth();

		if (is_array($page)) $page = $page['page'] ?? 1;
		else $page = 1;

		if (is_array($online)) $online = $online['online'] ?? 1;
		else $online = 0;

		$online = boolval($online ?? false);
		$skip = intval(($page - 1) * COUNT_PAGINATION);
		$take = COUNT_PAGINATION;

		$api = new API();
		if (getRole() == 'admin3') {
			$refCode = $_SESSION['refCode'];
			$responseUsers = ($online ?
				$api->getOnlineUsersByReferralCode($refCode ?? '0') :
				$api->getUsersByReferralCode($refCode ?? '0')
			);
		} else $responseUsers = $api->getUsers($skip, $take, $online);
		if ($responseUsers['status'] == 200) {
			$users = $responseUsers['response'];
		}
		$usersStats = $api->getUsersStats('', $refCode ?? '');
		if ($usersStats['status'] == 200) {
			$totalUsers = ($online ? $usersStats['response']['total_online_users'] : $usersStats['response']['total_users']);
		}

		$this->view('all', [
			'title' => __('users'),
			'pageTitle' => __('users'),
			'users' => $users ?? [],
			'totalUsers' => $totalUsers ?? 0,
			'assets' => [
				'js' => [
					'dataTables.min.js',
					'users.js'
				],
				'css' => 'dataTables.dataTables.min.css'
			],
			'page' => $page,
			'online' => $online
		]);
	}

	/**
	 * @throws Exception
	 */
	public function searchByLogin(Request $request): void
	{
		$login = $request->login;
		if (!empty($login)) {
			$api = new API();
			$data = $api->getUsersByLogin($login);
			if ($data['status'] == 200) {
				$user = $data['response'];
			}
		}
		$this->auth()->view('searchByLogin', [
			'title' => __('search by login'),
			'pageTitle' => __('search by login'),
			'login' => $login,
			'user' => $user ?? [],
			'assets' => [
				'js' => 'users.js'
			]
		]);
	}

	/**
	 * @throws Exception
	 */
	public function searchByRefCode($request): void
	{
		if (is_string($request)) {
			$referralCode = $request;
		} elseif (is_object($request)) {
			$online = $request->online;
			$referralCode = $request->referralCode;
		}

		if (!empty($referralCode)) {
			$api = new API();
			if (!empty($online)) {
				$data = $api->getOnlineUsersByReferralCode($referralCode);
			} else {
				$data = $api->getUsersByReferralCode($referralCode);
			}
			if ($data['status'] == 200) {
				$users = $data['response'];
			}
		}
		$this->auth()->view('searchByRefCode', [
			'title' => __('search by referral code'),
			'pageTitle' => __('search by referral code'),
			'referralCode' => $referralCode ?? '',
			'users' => $users ?? [],
			'assets' => [
				'js' => 'users.js'
			],
			'online' => !empty($online)
		]);
	}

	/**
	 * @throws Exception
	 */
	public function details(Request $request, string $id): void
	{
		$this->auth();

		$api = new API();

		if (getRole() == 'admin3') {
			$refCode = $_SESSION['refCode'];
			$users = $api->getUsersByReferralCode($refCode ?? '0');
			if ($users['status'] == 200) {
				$usersIds = array_column(($users['response'] ?? []), 'id');
				if (!in_array(intval($id), $usersIds)) (new Errors())->error403();
			} else {
				throw new Exception($users['response']['message']);
			}
		}

		$data = $api->getUserStats(intval($id));
		if ($data['status'] == 200) {
			$user = $data['response'];
		}
		$data = $api->getUser(intval($id));
		if ($data['status'] == 200) {
			$userInfo = $data['response'];
		}
		$this->view('details', [
			'title' => $userInfo['username'] ?? __('user'),
			'pageTitle' => $userInfo['username'] ?? __('user'),
			'userStats' => $user ?? [],
			'userInfo' => $userInfo ?? [],
			'userId' => $id,
			'assets' => [
				'js' => 'userDetails.js'
			]
		]);
	}

	/**
	 * @throws Exception
	 */
	public function block(Request $request): void
	{
		$api = new API();
		echo match ($request->type) {
			'lock' => json_encode($api->blockUser(intval($request->id))),
			'unlock' => json_encode($api->unblockUser(intval($request->id))),
			default => json_encode(['status' => 400, 'message' => 'bad request']),
		};
	}

	/**
	 * @throws Exception
	 */
	public function topUpBalance(Request $request): void
	{
		$this->auth();

		$amount = $request->amount ?? 0;
		$id = $request->id ?? 0;

		if (!empty($amount) && !empty($id)) {
			$api = new API();
			$result = $api->addBalance(intval($request->id), floatval($request->amount));
			if ($result['status'] == 201) {
				$message = __('balance successfully replenished');
			} else {
				$message = $result['response']['message'];
			}
		}

		redirect('/users/details/' . $id, [
			'message' => ($message ?? 'please send all required parameters')
		]);
	}

	/**
	 * @throws Exception
	 */
	public function writeOffBalance(Request $request): void
	{
		$this->auth();

		$amount = $request->amount ?? 0;
		$id = $request->id ?? 0;

		if (!empty($amount) && !empty($id)) {
			$api = new API();
			$result = $api->writeOffBalance(intval($request->id), floatval($request->amount));
			if ($result['status'] == 201) {
				$message = __('balance was successfully written off');
			} else {
				$message = $result['response']['message'];
			}
		}
		redirect('/users/details/' . $id, [
			'message' => ($message ?? 'please send all required parameters')
		]);
	}
}