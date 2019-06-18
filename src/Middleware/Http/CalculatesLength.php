<?php

namespace AOndra\SlickText\Middleware\Http;

use Psr\Http\Message\RequestInterface;

class CalculatesLength extends AbstractMiddleware
{
	/**
	 * Modify the HTTP Request.
	 *
	 * @param  \Psr\Http\Message\RequestInterface  $request
	 * @param  array  $options
	 *
	 * @return callable
	 */
	public function modify(RequestInterface $request, array $options = [])
	{
		$size = $request->getBody()->getSize();

		if ($size > 0) {
			$request = $request->withHeader('Content-Length', $size);
		}

		return $request;
	}
}
