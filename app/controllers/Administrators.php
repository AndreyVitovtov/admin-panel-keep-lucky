<?php

namespace App\Controllers;

use App\Models\Access;
use App\Models\AdminModel;
use App\Models\Model;
use App\Models\Role;
use App\Routes\Route;
use App\Utility\Request;

class Administrators extends Controller
{
	public function __construct()
	{
		$this->access = [
			'superadmin' => [
				'index',
				'add',
				'save',
				'delete',
				'edit',
				'update',
				'access',
				'saveAccess'
			]
		];
	}

	public function index(): void
	{
		$this->auth()->view('index', [
			'title' => __('administrators'),
			'pageTitle' => __('administrators'),
			'administrators' => (new Model)->query("
				SELECT a.*, r.`title` 
				FROM `admins` a,
				     `roles` r
				WHERE a.`role` = r.`id`
			", [], true)
		]);
	}

	public function add(): void
	{
		$this->auth()->view('add', [
			'title' => __('add administrator'),
			'pageTitle' => __('add administrator'),
			'roles' => (new Role)->get([], '', 'title'),
			'assets' => [
				'js' => 'administrators.js'
			]
		]);
	}

	public function save(Request $request): void
	{
		$this->auth();
		if (!is_null($request->name) && !is_null($request->login) && !is_null($request->password) &&
			!is_null($request->repeatPassword) && !is_null($request->role)) {
			if ($request->password != $request->repeatPassword) {
				redirect('/administrators/add', [
					'error' => __('password mismatch'),
					'name' => $request->name,
					'login' => $request->login
				]);
			} else {
				$referralCode = trim($request->referralCode);
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
					$admin->name = trim($request->name);
					$admin->login = trim($request->login);
					$admin->password = md5(trim($request->password));
					$admin->role = trim($request->role);
					$admin->avatar = '';
					$admin->referral_code = $referralCode ?? '';
					$admin->insert();
					redirect('/administrators', [
						'message' => __('administrator added')
					]);
				}
			}
		} else {
			redirect('/administrators/add', [
				'error' => __('submit all required parameters')
			]);
		}
	}

	public function delete(): void
	{
		$this->auth();
		$id = Request::get('id');
		if (!empty($id)) {
			$admin = new AdminModel();
			$admin->delete($id);
		}
		redirect('/administrators', [
			'message' => __('administrator deleted')
		]);
	}

	public function edit($id): void
	{
		$admin = new AdminModel();
		$admin = $admin->find($id)->toArray(['password']);
		if (empty($admin)) {
			redirect('/404');
		} else {
			$role = new Role();
			$this->auth()->view('edit', array_merge([
				'title' => __('edit administrator'),
				'pageTitle' => __('edit administrator'),
				'assets' => [
					'js' => 'administrators.js'
				]
			], $admin, ['roles' => $role->get([], '', 'title')]));
		}
	}

	public function update($id, Request $request): void
	{
		$this->auth();

		$admin = new AdminModel();
		$admin->find($id);
		if (!is_null($request->name) && !is_null($request->login) && !is_null($request->role)) {
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
						$admin->password = $request->password;
					}
				}
			}
			$referralCode = trim($request->referralCode);
			$admin->name = trim($request->name);
			$admin->login = trim($request->login);
			$admin->role = trim($request->role);
			$admin->referral_code = $referralCode ?? '';
			$admin->update();
			redirect('/administrators', [
				'message' => __('changes saved')
			]);
		} else {
			redirect('/administrators/edit/' . $id, [
				'error' => __('send all required parameters')
			]);
		}
	}

	public function access(Request $request): void
	{
		$request = $request->get();
		extract(getSessionParams(false));

		if (!empty($request) || !empty($adminId) && !empty($applicationId)) {
			$access = (new Access())->get([
				'admin_id' => $request['admin'] ?? $adminId,
				'application_id' => $request['application'] ?? $applicationId
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
		", [], true);

		$applications = (new \App\Models\Application())->get();

		$this->auth()->view('access', [
			'title' => __('access'),
			'pageTitle' => __('access'),
			'admins' => $admins,
			'assets' => [
				'js' => 'access.js'
			],
			'adminId' => $adminId ?? $request['admin'] ?? null,
			'applicationId' => $applicationId ?? $request['application'] ?? null,
			'routes' => Route::getRoutes(),
			'applications' => $applications,
			'accesses' => $accesses ?? []
		], false);
	}

	public function saveAccess(Request $request): void
	{
		$request = $request->get();

		(new Access())->query("
			DELETE FROM `accesses`
			WHERE `admin_id` = :adminId 
			  AND `application_id` = :applicationId
		", [
			'adminId' => $request['adminId'],
			'applicationId' => $request['applicationId']
		]);

		foreach ($request['accesses'] as $item) {
			$access = new Access();
			$access->admin_id = $request['adminId'];
			$access->application_id = $request['applicationId'];
			$access->controller = $item['controller'];
			$access->method = $item['method'];
			$access->available = 1;
			$access->insert();
		}

		redirect('/administrators/access', [
			'adminId' => $request['adminId'],
			'applicationId' => $request['applicationId'],
			'message' => __('changes saved')
		]);
	}
}