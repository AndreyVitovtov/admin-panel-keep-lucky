<?php

namespace App\Routes;

use App\Utility\Request;

class Route
{
	private static array $routes = [];

	public static function add(string $method, string $path, callable|array $handler, array $middlewares = []): void
	{
		self::$routes[] = [
			'method' => strtoupper($method),
			'path' => $path,
			'handler' => $handler,
			'middlewares' => $middlewares
		];
	}

	public static function get(string $path, callable|array $handler, array $middlewares = []): void
	{
		self::add('GET', $path, $handler, $middlewares);
	}

	public static function post(string $path, callable|array $handler, array $middlewares = []): void
	{
		self::add('POST', $path, $handler, $middlewares);
	}

	public static function match(string $requestUri, string $requestMethod): ?array
	{
		foreach (self::$routes as $route) {
			$pattern = preg_replace('#\{[\w]+\}#', '([\w\-]+)', $route['path']);
			$pattern = "#^" . $pattern . "$#";

			if (preg_match($pattern, $requestUri, $matches) && $route['method'] === strtoupper($requestMethod)) {
				array_shift($matches);
				$route['params'] = $matches;
				return $route;
			}
		}
		return null;
	}

	public static function handle(array $route)
	{
		global $cName;
		global $cMethod;
		[$cName, $cMethod] = $route['handler'];
		$cName = explode('\\', $cName);
		$cName = end($cName);

		foreach ($route['middlewares'] as $middleware) {
			(new $middleware())->handle();
		}

		$request = new Request();

		if (is_array($route['handler'])) {
			[$controller, $method] = $route['handler'];
			$instance = new $controller();

			return isset($route['params'])
				? $instance->$method($request, ...$route['params'])
				: $instance->$method($request);
		}

		return call_user_func($route['handler']);
	}

	public static function getRoutes(): array
	{
		return array_map(function ($item) {
			$controller = explode('\\', $item['handler'][0]);
			return [
				'controller' => end($controller),
				'method' => $item['handler'][1]
			];
		}, self::$routes);
	}
}
