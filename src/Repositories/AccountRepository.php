<?php

namespace AOndra\SlickText\Repositories;

use AOndra\SlickText\Exceptions\InvalidResponseException;
use AOndra\SlickText\Responses\Account\GetAccountResponse;

class AccountRepository extends AbstractRepository
{
	/**
	 * Get the Account Resource.
	 *
	 * @return \AOndra\SlickText\Responses\Account\GetAccountResponse
	 */
	public function get()
	{
		$response = $this->http->get('account');

		if (!$this->isValid($response)) {
			throw new InvalidResponseException('Invalid response for account information request');
		}

		return new GetAccountResponse($response);
	}
}
