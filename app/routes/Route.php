<?php

namespace App\Routes;

class Route {
	private static array $routes = [];

	public static function add(string $method, string $path, callable|array $handler, array $middlewares = []): void {
		self::$routes[] = [
			'method' => strtoupper($method),
			'path' => $path,
			'handler' => $handler,
			'middlewares' => $middlewares
		];
	}

	public static function get(string $path, callable|array $handler, array $middlewares = []): void {
		self::add('GET', $path, $handler, $middlewares);
	}

	public static function post(string $path, callable|array $handler, array $middlewares = []): void {
		self::add('POST', $path, $handler, $middlewares);
	}

	public static function match(string $requestUri, string $requestMethod): ?array {
		foreach (self::$routes as $route) {
			if ($route['path'] === $requestUri && $route['method'] === strtoupper($requestMethod)) {
				return $route;
			}
		}
		return null;
	}

	public static function handle(array $route) {
		foreach ($route['middlewares'] as $middleware) {
			(new $middleware())->handle();
		}

		if (is_array($route['handler'])) {
			[$class, $method] = $route['handler'];
			return (new $class())->$method();
		}

		return call_user_func($route['handler']);
	}
}
