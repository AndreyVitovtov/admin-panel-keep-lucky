<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\Role;
use App\Utility\Request;

class Auth extends Controller
{
	public function index(): void
	{
		$this->notAuth();

		if (isset($_COOKIE['rememberid'])) {
			$admin = new AdminModel();
			$adminId = decryptData($_COOKIE['rememberid'], CIPHER);

			$admin->find($adminId);
			$_SESSION['id'] = $admin->id;
			$_SESSION['login'] = $admin->login;
			$_SESSION['name'] = $admin->name;
			$_SESSION['role'] = (new Role)->find($admin->role)->title;
			$_SESSION['refCode'] = $admin->referral_code;
			$_SESSION['avatar'] = $admin->avatar;
			$_SESSION['adminId'] = $admin->admin_id;
			redirect('/');
		}

		$this->view('auth', [
			'title' => 'LogIn',
			'assets' => [
				'css' => 'auth.css'
			]
		]);
	}

	public function login(Request $request)
	{
		$this->notAuth();
		$admin = new AdminModel();
		$adminData = $admin->getOne([
			'login' => $request->login,
			'password' => md5($request->password)
		]);
		if (empty($adminData)) {
			redirect('/auth', [
				'error' => __('incorrect login or password')
			]);
		} else {
			$remember = $request->remember;
			if (!empty($remember)) {
				setcookie('rememberid', encryptData($adminData['id'], CIPHER), time() + (86400 * 30));
			}

			$_SESSION['id'] = $adminData['id'];
			$_SESSION['login'] = $adminData['login'];
			$_SESSION['name'] = $adminData['name'];
			$_SESSION['role'] = (new Role)->find($adminData['role'])->title;
			$_SESSION['refCode'] = $adminData['referral_code'];
			$_SESSION['avatar'] = $adminData['avatar'];
			$_SESSION['adminId'] = $adminData['admin_id'];
			redirect('/');
		}
	}

	public function logOut()
	{
		setcookie('rememberid', '', time() - 3600);
		unset($_COOKIE['rememberid']);

		sessionDestroy();
		redirect('/');
	}
}