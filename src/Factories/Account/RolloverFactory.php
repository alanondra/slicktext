<?php

namespace AOndra\SlickText\Factories\Account;

use stdClass;
use AOndra\SlickText\Concerns\ParsesDates;

class RolloverFactory
{
	use ParsesDates;

	/**
	 * Build a Rollover object from an array of data.
	 *
	 * @param array $data
	 *
	 * @return object
	 */
	public static function build(array $data)
	{
		$rollover = (object)[];

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
