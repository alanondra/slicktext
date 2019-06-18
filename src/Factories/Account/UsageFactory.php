<?php

namespace AOndra\SlickText\Factories\Account;

use AOndra\SlickText\Factories\AbstractFactory;

class UsageFactory extends AbstractFactory
{
	/**
	 * Build a Usage object from an array of data.
	 *
	 * @param array $data
	 *
	 * @return object
	 */
	public static function build(array $data)
	{
		$usage = (object)[];

		foreach ($data as $property => $value) {
			$prop = lcfirst($property);

			switch ($prop) {
				default:
					$usage->$prop = (is_numeric($value))
						? intval($value)
						: 0;
					break;
			}
		}

		return $usage;
	}
}
