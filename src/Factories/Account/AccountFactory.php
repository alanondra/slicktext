<?php

namespace AOndra\SlickText\Factories\Account;

use stdClass;
use AOndra\SlickText\Concerns\ParsesDates;

class AccountFactory
{
	use ParsesDates;

	/**
	 * Build an Account object from an array of data.
	 *
	 * @param array $data
	 *
	 * @return object
	 */
	public static function build(array $data)
	{
		$account = (object)[];

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
