<?php

namespace AOndra\SlickText\Repositories;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client as HttpClient;
use AOndra\SlickText\Contracts\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
	/**
	 * GuzzleHttp Client
	 *
	 * @var \GuzzleHttp\Client
	 */
	protected $http;

	/**
	 * Construct an instance of a Repository.
	 *
	 * @param \GuzzleHttp\Client $http
	 */
	public function __construct(HttpClient $http)
	{
		$this->http = $http;
	}

	/**
	 * Check if an HTTP Response is successful.
	 *
	 * @param \Psr\Http\Message\ResponseInterface $response
	 *
	 * @return boolean
	 */
	protected function isValid(ResponseInterface $response)
	{
		$status = $response->getStatusCode();

		if ($status < 200 || $status >= 300) {
			return false;
		}

		$type = $response->getHeaderLine('Content-Type');

		return (stripos($type, 'application/json') !== false);
	}
}
