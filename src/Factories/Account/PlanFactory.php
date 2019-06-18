<?php

namespace AOndra\SlickText\Factories\Account;

use AOndra\SlickText\Factories\AbstractFactory;

class PlanFactory extends AbstractFactory
{
	/**
	 * Build a Plan object from an array of data.
	 *
	 * @param array $data
	 *
	 * @return object
	 */
	public static function build(array $data)
	{
		$plan = (object)[];

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
