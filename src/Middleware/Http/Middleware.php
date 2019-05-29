<?php

namespace AOndra\SlickText\Middleware\Http;

use Psr\Http\Message\RequestInterface;

abstract class Middleware implements MiddlewareInterface
{
	/**
	 * Modify the HTTP Request.
	 *
	 * @param \Psr\Http\Message\RequestInterface $request
	 * @param array $options
	 *
	 * @return callable
	 */
	abstract protected function modify(RequestInterface $request, array $options = []);

	/**
	 * Handle execution of Middleware instance as a callable.
	 *
	 * @param callable $next
	 *
	 * @return callable
	 */
	public function __invoke(callable $next)
	{
		return function (RequestInterface $request, array $options = []) use ($next) {
			$request = $this->modify($request, $options);

			return $next($request, $options);
		};
	}
}
