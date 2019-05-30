<?php

namespace AOndra\SlickText\Concerns;

use Throwable;
use Carbon\{
	Carbon,
	CarbonTimeZone
};

trait ParsesDates
{
	/**
	 * The name of the SlickText TimeZone.
	 *
	 * @var string
	 */
	protected static $timeZoneName = 'America/Los_Angeles';

	/**
	 * Cached CarbonTimeZone instance.
	 *
	 * @var \Carbon\CarbonTimeZone
	 */
	protected static $timeZone;

	/**
	 * Get the CarbonTimeZone instance.
	 *
	 * @return \Carbon\CarbonTimeZone
	 */
	protected static function getTimeZone()
	{
		return static::$timeZone ?: (static::$timeZone = new CarbonTimeZone(static::$timeZoneName));
	}

	/**
	 * Attempt to parse a timestamp into a Carbon object.
	 *
	 * @param mixed $timestamp
	 */
	protected static function parseDate($timestamp)
	{
		try {
			$dt = Carbon::parse($timestamp, static::getTimeZone());

			if ($dt->timestamp <= 0) {
				$dt = null;
			}
		} catch (Throwable $exc) {
			$dt = null;
		}

		return $dt;
	}
}
