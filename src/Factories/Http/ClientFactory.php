<?php

namespace AOndra\SlickText\Factories\Http;

use GuzzleHttp\{
	Client as HttpClient,
	HandlerStack,
	RequestOptions
};
use function GuzzleHttp\choose_handler;
use AOndra\SlickText\Middleware\Http\{
	AuthenticatesRequest,
	CalculatesLength
};

class ClientFactory
{
	/**
	 * Create an instance of a Guzzle HTTP Client.
	 *
	 * @param string $url
	 * @param string $publicKey
	 * @param string $privateKey
	 * @param array $options
	 *
	 * @return \GuzzleHttp\Client
	 */
	public static function createClient(string $url, string $publicKey, string $privateKey, array $options = [])
	{
		$config = static::getClientOptions($options);

		$stack = static::createHandlerStack();

		$stack->push(new CalculatesLength);
		$stack->push(new AuthenticatesRequest($publicKey, $privateKey));

		$config['handler'] = $stack;
		$config['base_uri'] = $url;

		return new HttpClient($config);
	}

	/**
	 * Get the configuration options for the Guzzle HTTP Client.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	protected static function getClientOptions(array $options = [])
	{
		$defaults = [
			RequestOptions::ALLOW_REDIRECTS => [
				'max' => 3,
				'strict' => true,
				'referer' => true,
				'protocols' => ['https'],
				'track_redirects' => true,
			],
		];

		$required = [
			RequestOptions::HTTP_ERRORS => false,
			RequestOptions::SYNCHRONOUS => true,
		];

		return array_replace($defaults, $options, $required);
	}

	/**
	 * Create a HandlerStack.
	 *
	 * @return \GuzzleHttp\HandlerStack
	 */
	protected static function createHandlerStack()
	{
		$stack = new HandlerStack();

		$stack->setHandler(choose_handler());

		return $stack;
	}
}
