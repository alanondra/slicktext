<?php

namespace AOndra\SlickText\Middleware\Http;

class AuthenticatesRequest
{
	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * Construct an instance of AuthenticatesRequest.
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function __construct(string $username, string $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * Modify the HTTP Request.
	 *
	 * @param \Psr\Http\Message\RequestInterface $request
	 * @param array $options
	 *
	 * @return callable
	 */
	public function modify(RequestInterface $request, array $options = array())
	{
		if (!empty($this->username) && !empty($this->password)) {
			$request = $request->withHeader('Authorization', $this->getAuthToken());
		}

		return $request;
	}

	/**
	 * Get the value for the Authorization header.
	 *
	 * @return string
	 */
	protected function getAuthToken()
	{
		return sprintf('Basic %s', $this->generateAuthKey());
	}

	/**
	 * Generate the Authorization key value.
	 *
	 * @return string
	 */
	protected function generateAuthKey()
	{
		return base64_encode(sprintf('%s:%s', $this->username, $this->password));
	}
}
