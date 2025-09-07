<?php

namespace App\Controllers;

use App\Models\Access;
use App\Models\AccessesOptions;
use App\Models\AccessesSelected;
use App\Models\AdminModel;
use App\Models\Model;
use App\Models\Role;
use App\Routes\Route;
use App\Utility\Request;

class Administrators extends Controller
{
	public function __construct()
	{
//		$this->access = [
//			'superadmin' => [
//				'index',
//				'add',
//				'save',
//				'delete',
//				'edit',
//				'update',
//				'access',
//				'saveAccess'
//			]
//		];
	}

	public function index(): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['all'])) (new Errors())->error403();
		else {
			$this->view('index', [
				'title' => __('administrators'),
				'pageTitle' => __('administrators'),
				'administrators' => (new Model)->query("
					SELECT a.*, r.`title` 
					FROM `admins` a,
					     `roles` r
					WHERE a.`role` = r.`id`
				", [], true),
				'accesses' => $accesses
			]);
		}
	}

	public function add(): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['all'])) (new Errors())->error403();
		else {
			$this->view('add', [
				'title' => __('add administrator'),
				'pageTitle' => __('add administrator'),
				'roles' => (new Role)->get([], '', 'title'),
				'assets' => [
					'js' => 'administrators.js'
				]
			]);
		}
	}

	/**
	 * @throws \Exception
	 */
	public function save(Request $request): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['add'])) (new Errors())->error403();
		else {
			$apk = $request->apk ?? [];
			$shops = $request->shops ?? [];
			if (!is_null($request->name) && !is_null($request->login) && !is_null($request->password) &&
				!is_null($request->repeatPassword) && !is_null($request->role)) {
				if ($request->password != $request->repeatPassword) {
					redirect('/administrators/add', [
						'error' => __('password mismatch'),
						'name' => $request->name,
						'login' => $request->login
					]);
				} else {
					$referralCode = trim($request->referralCode ?? '');
					if (!empty($referralCode)) $arrReferralCode = [$referralCode];
					else $arrReferralCode = [];

					$admin = new AdminModel();
					if (!empty($admin->get([
						'login' => $request->login
					]))) {
						redirect('/administrators/add', [
							'error' => __('administrator with this login already exists'),
							'name' => $request->name,
							'login' => $request->login
						]);
					} else {
						$roleId = trim($request->role);
						$role = (new Role)->getOne(['id' => $roleId]);
						$role = ($role['title'] == 'admin' ? 'ADMIN' : ($role['title'] == 'superadmin' ? 'SUPER_ADMIN' : 'ADMIN'));

						$password = encryptData(md5(trim($request->password)), CIPHER);

						$api = new \App\API\API();
						$res = $api->createAdmin(trim($request->login), $password, $role, $apk, $shops, $arrReferralCode);
						if ($res['status'] != 201) {
							redirect('/administrators/add', [
								'error' => $res['response']['message']
							]);
						} else {
							$adminId = $res['response']['id'];

							$admin->name = trim($request->name);
							$admin->login = trim($request->login);
							$admin->password = $password;
							$admin->role = trim($request->role);
							$admin->avatar = '';
							$admin->admin_id = $adminId;
							$admin->referral_code = $referralCode;
							$admin->insert();
							redirect('/administrators', [
								'message' => __('administrator added')
							]);
						}
					}
				}
			} else {
				redirect('/administrators/add', [
					'error' => __('submit all required parameters')
				]);
			}
		}
	}

	/**
	 * @throws \Exception
	 */
	public function delete(): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['all'])) (new Errors())->error403();
		else {
			$id = Request::get('id');
			if (!empty($id)) {
				$admin = new AdminModel();
				$admin = $admin->find($id);

				$api = new \App\API\API();
				$api->deleteAdmin($admin->admin_id);

				$admin->delete($id);
			}
			redirect('/administrators', [
				'message' => __('administrator deleted')
			]);
		}
	}

	/**
	 * @throws \Exception
	 */
	public function edit($id): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['all'])) (new Errors())->error403();
		else {
			$admin = new AdminModel();
			$admin = $admin->find($id)->toArray(['password']);
			if (empty($admin)) {
				redirect('/404');
			} else {
				$role = new Role();

				$api = new \App\API\API();
				$resAdmin = $api->getAdminById($admin['admin_id']);
				if ($resAdmin['status'] == 200) $adminApi = $resAdmin['response'];
				else $admin = [];
				$selectedApks = $adminApi['apks'] ?? [];
				$selectedShops = $adminApi['shops'] ?? [];
				$selectedReferralCode = $adminApi['referral_codes'][0] ?? '';
				//TODO: Referral codes array?

				$this->view('edit', array_merge([
					'title' => __('edit administrator'),
					'pageTitle' => __('edit administrator'),
					'selectedApks' => $selectedApks,
					'selectedShops' => $selectedShops,
					'selectedReferralCode' => $selectedReferralCode,
					'assets' => [
						'js' => 'administrators.js'
					]
				], $admin, ['roles' => $role->get([], '', 'title')]));
			}
		}
	}

	/**
	 * @throws \Exception
	 */
	public function update($id, Request $request): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['all'])) (new Errors())->error403();
		else {
			$admin = new AdminModel();
			$admin->find($id);
			if (!is_null($request->name) && !is_null($request->login) && !is_null($request->role)) {
				$api = new \App\API\API();
				$referralCode = trim($request->referralCode ?? '');
				if(empty($referralCode)) $referralCode = [];
				else $referralCode = [$referralCode];
				$res = $api->updateAdmin(
					$admin->admin_id,
					$request->apk ?? [],
					$request->shop ?? [],
					$referralCode
				);
				if ($res['status'] != 200) {
					redirect('/administrators/edit/' . $id, [
						'error' => $res['response']['message'] ?? 'Update failed'
					]);
				} else {
					if (!is_null($request->password)) {
						if (is_null($request->repeatPassword)) {
							redirect('/administrators/edit/' . $id, [
								'error' => __('send all required parameters')
							]);
							exit;
						} else {
							if ($request->password != $request->repeatPassword) {
								redirect('/administrators/edit/' . $id, [
									'error' => __('password mismatch')
								]);
								exit;
							} else {
								$password = encryptData(md5($request->password), CIPHER);
								$admin->password = $password;
							}
						}
					}
					$referralCode = trim($request->referralCode ?? '');
					$admin->name = trim($request->name);
					$admin->login = trim($request->login);
					$admin->role = trim($request->role);
					$admin->referral_code = $referralCode ?? '';
					$admin->update();

					$resApi = $api->updateAdminLoginPassword($admin->admin_id, trim($request->login), $password ?? '');
					if ($resApi['status'] != 200) {
						redirect('/administrators', [
							'error' => 'Update failed2'
						]);
					}

					redirect('/administrators', [
						'message' => __('changes saved')
					]);
				}
			} else {
				redirect('/administrators/edit/' . $id, [
					'error' => __('send all required parameters')
				]);
			}
		}
	}

	public function access(Request $request): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['access'])) (new Errors())->error403();
		else {
			$request = $request->get();
			extract(getSessionParams(false));

			if (!empty($request) || !empty($adminId)) {
				$access = (new Access())->get([
					'admin_id' => $request['admin'] ?? $adminId
				]);
				$accesses = [];
				foreach ($access as $a) {
					$accesses[$a['controller']][] = $a['method'];
				}
			}

			$admins = (new AdminModel())->query("
			SELECT a.`id`, a.`name`, a.`login`, r.`title`
			FROM `admins` a,
			     `roles` r
			WHERE a.`role` = r.`id`
			AND r.`title` = 'admin'
		", [], true);

			$accessOptions = (new AccessesOptions)->get();
			$accessesSelectedRes = (new AccessesSelected())->query("
			SELECT o.`section`, o.`title`
			FROM `accesses_selected` s,
			     `accesses_options` o
			WHERE s.`access_id` = o.`id`
			AND s.`admin_id` = :adminId
		", [
				'adminId' => $request['admin'] ?? $adminId ?? 0
			], true);
			$accessesSelected = [];
			foreach ($accessesSelectedRes as $accessSelected) {
				$accessesSelected[$accessSelected['section']][] = $accessSelected['title'];
			}

			$this->view('access', [
				'title' => __('access'),
				'pageTitle' => __('access'),
				'admins' => $admins,
				'assets' => [
					'js' => 'access.js'
				],
				'adminId' => $adminId ?? $request['admin'] ?? null,
				'routes' => Route::getRoutes(),
				'accesses' => $accesses ?? [],

				'accessesOptions' => $accessOptions,
				'accessesSelected' => $accessesSelected
			], false);
		}
	}

	public function saveAccess(Request $request): void
	{
		$this->auth();

		$accesses = getAccesses();
		if (isRole('admin') && !isset($accesses['administrators']['access'])) (new Errors())->error403();
		else {
			$request = $request->get();

			(new AccessesSelected())->query("
			DELETE FROM `accesses_selected`
			WHERE `admin_id` = :adminId
		", [
				'adminId' => $request['adminId']
			]);

			$accessesOptionsRes = (new AccessesOptions)->get();
			$accessesOptions = [];
			foreach ($accessesOptionsRes as $accessOption) {
				$accessesOptions[$accessOption['section']][$accessOption['title']] = $accessOption['id'];
			}

			foreach ($request['accesses'] as $item) {
				$access = new AccessesSelected();
				$access->admin_id = $request['adminId'];
				$access->access_id = $accessesOptions[$item['section']][$item['option']];
				$access->available = 1;
				$access->insert();
			}

			redirect('/administrators/access', [
				'adminId' => $request['adminId'],
				'message' => __('changes saved')
			]);
		}
	}
}