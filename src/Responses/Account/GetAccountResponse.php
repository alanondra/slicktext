<?php

namespace AOndra\SlickText\Responses\Account;

use AOndra\SlickText\Responses\Response;
use AOndra\SlickText\Factories\Account\{
	AccountFactory,
	PlanFactory,
	RolloverFactory,
	UsageFactory
};

/**
 * @property-read \stdClass $account
 * @property-read \stdClass $plan
 * @property-read array $rollovers
 * @property-read \stdClass $usage
 */
class GetAccountResponse extends Response
{
	/**
	 * Account Information
	 *
	 * @var \stdClass
	 */
	protected $account;

	/**
	 * Plan Information
	 *
	 * @var \stdClass
	 */
	protected $plan;

	/**
	 * Rollover Information
	 *
	 * @var array
	 */
	protected $rollovers = [];

	/**
	 * Usage Information
	 *
	 * @var \stdClass
	 */
	protected $usage;

	/**
	 * Handle the parsed Response content.
	 *
	 * @param array $data
	 */
	protected function handle(array $data)
	{
		$factories = [
			'account' => AccountFactory::class,
			'plan' => PlanFactory::class,
			'usage' => UsageFactory::class,
		];

		foreach ($factories as $property => $factory) {
			if (key_exists($property, $data)) {
				$value = $data[$property];
				$this->$property = $factory::create($value);
			}
		}

		if (key_exists('rollovers', $data)) {
			foreach ($data['rollovers'] as $rollover) {
				$this->rollovers[] = RolloverFactory::create($rollover);
			}
		}
	}
}
