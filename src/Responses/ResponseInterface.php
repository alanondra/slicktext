<?php

namespace AOndra\SlickText\Responses;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface
{
	/**
	 * Check if a Response is successful.
	 *
	 * @return boolean
	 */
	public function isSuccessful() : bool;
}
