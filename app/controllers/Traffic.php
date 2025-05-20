<?php

namespace App\Controllers;

use App\API\API;
use Exception;

class Traffic extends Controller
{
	/**
	 * @throws Exception
	 */
	public function stats(): void
	{
		$this->auth();

		$api = new API();
		$result = $api->getTrafficStats();
		if ($result['status'] == 200) {
			$traffic = $result['response'][0];
		} else {
			throw new Exception($result['response']['message']);
		}

		$this->view('stats', [
			'title' => __('traffic stats'),
			'pageTitle' => __('traffic stats'),
			'assets' => [
				'js' => 'traffic.js'
			],
			'totalTraffic' => $traffic['total_traffic'] ?? 0,
			'totalCost' => $traffic['total_cost'] ?? 0
		]);
	}
}