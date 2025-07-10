<?php

use App\Models\AdminModel;
use App\Models\Role;
use App\Routes\Route;

sessionStart();

date_default_timezone_set(TIMEZONE);

restoreSession();
setDefaultApplication();

function isDev(): void
{
	if (DEV) {
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
	} else {
		ini_set('error_reporting', 0);
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
	}
}

function getControllerAndMethod(): array
{
	$url = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI']), function ($v) {
		return !empty(trim($v));
	}));
	if (empty($url) || (isset($url[0][0]) && $url[0][0] === '?')) {
		$controllerName = 'Main';
		$method = 'index';
		if (isset($url[0][0]) && $url[0][0] === '?') {
			$params = explode('&', substr($url[0], 1));
			$params = array_map(function ($v) {
				$v = explode('=', $v);
				return [
					$v[0] => $v[1]
				];
			}, $params);
		} else {
			$params = [];
		}
	} else {
		$controllerName = ucfirst($url[0]);
		if (strpos($controllerName, '?') !== false) {
			$cn = explode('?', $controllerName);
			$controllerName = $cn[0];
			$params = explode('&', $cn[1]);
			$params = array_map(function ($v) {
				$v = explode('=', $v);
				return [
					$v[0] => $v[1]
				];
			}, $params);
		}
		if (isset($url[1])) {
			if (is_numeric($url[1]) || empty($url[1])) {
				$method = 'index';
			} else $method = $url[1];
			if (strpos($method, '?') !== false) {
				$m = explode('?', $method);
				$method = $m[0];
				$params = explode('&', $m[1]);
				$params = array_map(function ($v) {
					$v = explode('=', $v);
					return [
						$v[0] => $v[1]
					];
				}, $params);
			}
		} else $method = 'index';
		unset($url[0], $url[1]);
		$params = array_merge($params ?? [], explode('/', implode('/', $url)));
	}

	global $cName;
	global $cMethod;
	$cName = $controllerName;
	$cMethod = $method;

	return [
		'controllerName' => $controllerName,
		'method' => $method,
		'params' => $params
	];
}

function handleRoute()
{
	isDev();

	$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$requestMethod = $_SERVER['REQUEST_METHOD'];

	if ($route = Route::match($requestUri, $requestMethod)) {
		return Route::handle($route);
	}

	extract(getControllerAndMethod());

	$controllerClass = "App\\Controllers\\$controllerName";
	if (!class_exists($controllerClass)) {
		header("HTTP/1.0 404 Not Found");
		(new App\Controllers\Errors)->error404();
		exit;
	}

	$controllerInstance = new $controllerClass();
	if (method_exists($controllerInstance, $method)) {
		$params[] = (new \App\Utility\Request());
		$params = array_filter($params, function ($v) {
			return !empty($v);
		});
		$controllerInstance->$method(...$params);
	} else {
		header("HTTP/1.0 404 Not Found");
		(new App\Controllers\Errors)->error404();
	}
}

function isAuth(): bool
{
	return !empty($_SESSION['id']);
}

function getRole()
{
	return $_SESSION['role'] ?? 'guest';
}

function assets($path): string
{
	$absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/app/assets/' . $path;
	if (file_exists($absolutePath)) {
		$version = filemtime($absolutePath);
		return BASE_URL . 'app/assets/' . $path . '?v=' . $version;
	} else {
		return BASE_URL . 'app/assets/' . $path;
	}
}

function data($path): string
{
	return $_SERVER['DOCUMENT_ROOT'] . '/app/data/' . $path;
}

function redirect($location, $params = [], $var = true)
{
	if ($var && !empty($params)) setSessionParams($params);
	header("Location: " . $location . (!$var && !empty($params) ? '?' . http_build_query($params) : ''));
	exit;
}

function sessionStart()
{
	session_start();
}

function sessionDestroy()
{
	session_destroy();
}

function sessionUpdate($params = [])
{
	foreach ($params as $k => $v) {
		$_SESSION[$k] = $v;
	}
}

function theme(): string
{
	return $_COOKIE['theme'] ?? 'light';
}

function dd($var)
{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	die();
}

function br($count = 1): string
{
	return str_repeat("<br>", $count);
}

function user($param, $alter = null)
{
	return !empty($_SESSION[$param]) ? $_SESSION[$param] : $alter;
}

function menuIsActive($c, $m = null, $bool = false)
{
	global $cName;
	global $cMethod;
	if ($bool) return ($cName == $c && (is_null($m) || $cMethod == $m));
	return ($cName == $c && (is_null($m) || $cMethod == $m) ? 'active' : '');
}

function getCurrentLang()
{
	return $_SESSION['lang'] ?? $_COOKIE['lang'] ?? DEFAULT_LANG;
}

function getLocalization()
{
	global $localization;
	if (!empty($localization)) return $localization;
	$localization = json_decode(file_get_contents('app/data/lang/' . getCurrentLang() . '.json'), true);
	return $localization;
}

function getLanguage($item, $abbr = null): string
{
	return LANGUAGES[($abbr ?? getCurrentLang())][$item] ?? '';
}

function __($str, $params = [])
{
	$localization = getLocalization();
	$text = $localization[$str] ?? $str;
	array_map(function ($k, $v) use (&$text) {
		$text = preg_replace('/\{' . $k . '\}/', $v, $text);
	}, array_keys($params), $params);
	return $text;
}

function getCurrentUrl($encodeBase64 = false): string
{
	return ($encodeBase64 ? base64_encode($_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI']);
}

function setSessionParams($params)
{
	$_SESSION['extract'] = $params;
}

function getSessionParams($clear = true)
{
	$extract = $_SESSION['extract'] ?? [];
	if ($clear) clearSessionParams();
	return $extract;
}

function clearSessionParams()
{
	unset($_SESSION['extract']);
}

function checkAccess($access = [], $forbid = []): bool
{
	if ((!empty($access) && !in_array(getRole(), $access)) || (!empty($forbid) && in_array(getRole(), $forbid))) return false;
	return true;
}

function menuItem($title, $icon, $address, $controller, $access = [], $forbid = []): string
{
	$accesses = getAccesses();
	if (isAuth() && $_SESSION['role'] !== 'superadmin' && !in_array('index', $accesses[$controller] ?? [])) return '';
	if (!checkAccess($access, $forbid)) return '';
	return '<a href="' . $address . '">
        <div class="menu-item ' . menuIsActive($controller) . '">
            <i class="icon-' . $icon . '"></i> ' . __($title) . '
        </div>
    </a>';
}

function menuRoll($title, $icon, $controller, $items = [], $access = [], $forbid = []): string
{
	if (!checkAccess($access, $forbid)) return '';
	$menuItems = '';
	$accesses = getAccesses();
	foreach ($items as $item) {
		if (isAuth() && $_SESSION['role'] !== 'superadmin' && !in_array($item['method'], $accesses[$controller] ?? [])) continue;
		$menuItems .= '<a href="' . $item['address'] . '" class="' . menuIsActive($controller, $item['method']) . '">
                <div>' . $item['title'] . '</div>
            </a>';
	}
	if (empty($menuItems)) return '';
	return '<div class="menu-item">
        <div class="flex-between">
            <span><i class="icon-' . $icon . '"></i> ' . $title . '</span>
            <span class="menu-angle"><i class="icon-angle-right"></i></span>
        </div>
        <div class="menu-roll ' . (menuIsActive($controller, null, true) ? 'activate' : '') . '">
            ' . $menuItems . '
        </div>
    </div>';
}

function encryptData($data, $key): string
{
	$cipher = "AES-256-CBC";
	$iv_length = openssl_cipher_iv_length($cipher);
	$iv = openssl_random_pseudo_bytes($iv_length);
	$encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	return base64_encode($iv . $encrypted);
}

function decryptData($data, $key)
{
	$cipher = "AES-256-CBC";
	$iv_length = openssl_cipher_iv_length($cipher);
	$data = base64_decode($data);
	$iv = substr($data, 0, $iv_length);
	$data = substr($data, $iv_length);
	return openssl_decrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
}

function setDefaultApplication(): void
{
	$applications = getApplicationsByAccess();
	if (empty($_SESSION['application']) && !empty($applications)) {
		$_SESSION['application'] = $applications[0];
	}
}

function getApplicationsByAccess(): array
{
	return (new \App\Models\Access())->query("
		SELECT s.*
		FROM `accesses` acc,
		     `shops` s
		WHERE acc.`application_id` = s.`id`
		AND acc.`admin_id` = :adminId
		GROUP BY s.`id`
		ORDER BY s.`id`
	", [
		'adminId' => $_SESSION['id'] ?? 0
	], true);
}

function getAccesses(): array
{
	$accesses = [];
	foreach ((new \App\Models\Access())->get([
		'application_id' => $_SESSION['application']['id'] ?? 0,
		'admin_id' => $_SESSION['id'] ?? 0
	]) as $access) {
		$accesses[$access['controller']][] = $access['method'];
	}
	return $accesses;
}

function pagination(int $total, int $perPage, $page): string
{
	$pages = ceil($total / $perPage);
	$current = isset($page) ? (int)$page : 1;
	$current = max(1, min($current, $pages));

	if ($pages <= 1) return '';

	$html = '';

	if ($current > 1) {
		$html .= '<a href="?page=' . ($current - 1) . '"><i class="icon-left-open-mini"></i></a>';
	}

	for ($i = 1; $i <= $pages; $i++) {
		if (
			$i == 1 || $i == $pages ||
			($i >= $current - 2 && $i <= $current + 2)
		) {
			if ($i == $current) {
				$html .= '<span class="active">' . $i . '</span>';
			} else {
				$html .= '<a href="?page=' . $i . '">' . $i . '</a>';
			}
		} elseif (
			$i == 2 && $current > 4 ||
			$i == $pages - 1 && $current < $pages - 3
		) {
			$html .= '<span class="dots">...</span>';
		}
	}

	if ($current < $pages) {
		$html .= '<a href="?page=' . ($current + 1) . '"><i class="icon-right-open-mini"></i></a>';
	}

	return $html;
}

function restoreSession(): void
{
	if (!isAuth() && isset($_COOKIE['rememberid'])) {
		$admin = new AdminModel();
		$adminId = decryptData($_COOKIE['rememberid'], CIPHER);

		$admin->find($adminId);
		$_SESSION['id'] = $admin->id;
		$_SESSION['login'] = $admin->login;
		$_SESSION['name'] = $admin->name;
		$_SESSION['role'] = (new Role)->find($admin->role)->title;
		$_SESSION['avatar'] = $admin->avatar;
	}
}

function isRole($role): bool
{
	return (getRole() === $role);
}