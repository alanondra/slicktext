<?php

namespace AOndra\SlickText\Contracts\Http;

interface MiddlewareInterface
{
	/**
	 * Handle execution of Middleware instance as a callable.
	 *
	 * @param callable $next
	 *
	 * @return callable
	 */
	public function __invoke(callable $next);
}
