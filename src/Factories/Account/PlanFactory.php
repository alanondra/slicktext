<?php

namespace AOndra\SlickText\Factories\Account;

use stdClass;

class PlanFactory
{
	/**
	 * Create a Plan object.
	 *
	 * @param array $data
	 *
	 * @return \stdClass
	 */
	public static function create(array $data)
	{
		$plan = new stdClass;

		foreach ($data as $property => $value) {
			$prop = lcfirst($property);

			switch ($prop) {
				case 'rollovers':
					$plan->$prop = boolval($value);
					break;

				default:
					$plan->$prop = $value;
					break;
			}
		}

		return $plan;
	}
}
