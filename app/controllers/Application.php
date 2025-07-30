<?php

namespace App\Controllers;

class Application extends Controller
{
	public function change($appId): void
	{
		$this->auth();
		if (!is_string($appId)) $appId = 0;
//		$applications = getApplicationsByAccess();
//		$applications = array_combine(array_column($applications, 'id'), $applications);
//		if (isset($applications[$appId])) {
//			$_SESSION['application'] = $applications[$appId];
//		}
		redirect('/');
	}
}