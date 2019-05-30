<?php

namespace AOndra\SlickText\Factories\Account;

use stdClass;

class UsageFactory
{
	/**
	 * Create a Usage object.
	 *
	 * @param array $data
	 *
	 * @return \stdClass
	 */
	public static function create(array $data)
	{
		$usage = new stdClass;

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
