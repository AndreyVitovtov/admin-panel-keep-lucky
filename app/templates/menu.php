<?php
global $menu;
$menu = [
	[
		'title' => __('dashboard'),
		'icon' => 'chart-bar-1',
		'address' => '/',
		'controller' => 'Main'
	], [
		'title' => __('users'),
		'icon' => 'group',
		'address' => '/users',
		'controller' => 'Users'
	], [
		'title' => __('traffic'),
		'icon' => 'traffic-cone',
		'address' => '/traffic',
		'controller' => 'Traffic'
	], [
		'title' => __('administrators'),
		'icon' => 'star',
		'controller' => 'Administrators',
		'assets' => ['superadmin'],
		'items' => [
			[
				'title' => __('add'),
				'address' => '/administrators/add',
				'method' => 'add'
			], [
				'title' => __('all'),
				'address' => '/administrators',
				'method' => 'index'
			], [
				'title' => __('access'),
				'address' => '/administrators/access',
				'method' => 'access'
			]
		]
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
			menuRoll($title, $icon, $controller, $items, $access ?? [], $forbid ?? []) :
			menuItem($title, $icon, $address, $controller, $assets ?? [], $forbid ?? []);
	}, $menu)) ?>
</div>