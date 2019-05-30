<?php

namespace AOndra\SlickText\Factories\Account;

use stdClass;
use AOndra\SlickText\Concerns\ParsesDates;

class AccountFactory
{
	use ParsesDates;

	/**
	 * Create an Account object.
	 *
	 * @param array $data
	 *
	 * @return \stdClass
	 */
	public static function create(array $data)
	{
		$account = new stdClass;

		foreach ($data as $property => $value) {
			$prop = lcfirst($property);

			switch ($prop) {
				case 'dateJoined':
				case 'nextBilling':
				case 'lastLogin':
					$account->$prop = static::parseDate($value);

					break;

				default:
					$account->$prop = $value;
					break;
			}
		}

		return $account;
	}
}
