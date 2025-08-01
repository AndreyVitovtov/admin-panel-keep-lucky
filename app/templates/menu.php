<?php
global $menu;
$menu = [
	[
		'title' => __('traffic'),
		'icon' => 'chart-bar-1',
		'address' => '/',
		'controller' => 'Main',
        'section' => 'traffic'
	], [
		'title' => __('users'),
		'icon' => 'group',
		'address' => '/users',
		'controller' => 'Users',
        'section' => 'users'
	],
//    [
//		'title' => __('users'),
//		'icon' => 'group',
//		'controller' => 'Users',
//		'items' => [
//			[
//				'title' => __('all'),
//				'address' => '/users/all',
//				'method' => 'all'
//			], [
//				'title' => __('search by login'),
//				'address' => '/users/searchByLogin',
//				'method' => 'searchByLogin'
//			], [
//				'title' => __('search by referral code'),
//				'address' => '/users/searchByRefCode',
//				'method' => 'searchByRefCode'
//			]
//		]
//	],
// [
//		'title' => __('traffic'),
//		'icon' => 'traffic-cone',
//		'address' => '/traffic',
//		'controller' => 'Traffic'
//	],
//    [
//		'title' => __('traffic'),
//		'icon' => 'traffic-cone',
//		'controller' => 'Traffic',
//		'items' => [
//			[
//				'title' => __('stats'),
//				'address' => '/traffic/stats',
//				'method' => 'stats'
//			]
//		]
//	],
	[
		'title' => __('administrators'),
		'icon' => 'star',
		'controller' => 'Administrators',
		'assets' => ['superadmin'],
		'items' => [
			[
				'title' => __('add'),
				'address' => '/administrators/add',
				'method' => 'add',
                'option' => 'add'
			], [
				'title' => __('all'),
				'address' => '/administrators',
				'method' => 'index',
				'option' => 'all'
			], [
				'title' => __('access'),
				'address' => '/administrators/access',
				'method' => 'access',
				'option' => 'access'
			]
		],
        'section' => 'administrators'
	]
];
?>

<div class="logo">
    <a href="/">
		<?= PROJECT_NAME ?>
    </a>
</div>
<div class="menu-items">
	<?= implode('', array_map(function ($i) {
		extract($i);
		return isset($items) ?
			menuRoll($title, $icon, $controller, $items, $access ?? [], $forbid ?? [], $section) :
			menuItem($title, $icon, $address, $controller, $assets ?? [], $forbid ?? [], $section);
	}, $menu)) ?>
</div>