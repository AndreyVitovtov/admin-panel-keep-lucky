<?php

namespace App\Routes;

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
			if ($route['path'] === $requestUri && $route['method'] === strtoupper($requestMethod)) {
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

		if (is_array($route['handler'])) {
			[$controller, $method] = $route['handler'];
			return (new $controller())->$method();
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
