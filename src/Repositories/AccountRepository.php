<?php

namespace AOndra\SlickText\Repositories;

use AOndra\SlickText\Responses\Account\GetAccountResponse;

class AccountRepository extends Repository
{
	/**
	 * Get the Account Resource.
	 *
	 * @return \AOndra\SlickText\Responses\Account\GetAccountResponse
	 */
	public function get()
	{
		$response = $this->http->get('account');

		return new GetAccountResponse($response);
	}
}
