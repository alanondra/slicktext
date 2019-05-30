<?php

namespace AOndra\SlickText\Factories\Account;

use stdClass;
use AOndra\SlickText\Concerns\ParsesDates;

class RolloverFactory
{
	use ParsesDates;

	/**
	 * Create a Rollover object.
	 *
	 * @param array $data
	 *
	 * @return \stdClass
	 */
	public static function create(array $data)
	{
		$rollover = new stdClass;

		foreach ($data as $property => $value) {
			$prop = lcfirst($property);

			switch ($prop) {
				case 'amountAvailable':
				case 'amountUsed':
				case 'totalAmount':
					$rollover->$prop = (is_numeric($value))
						? intval($value)
						: null;

					break;

				case 'expires':
					$rollover->$prop = static::parseDate($value);

					break;

				default:
					$rollover->$prop = $value;
					break;
			}
		}

		return $rollover;
	}
}
