<?php

namespace AOndra\SlickText\Contracts;

interface FactoryInterface
{
	/**
	 * Build an object from an array of data.
	 *
	 * @param array $data
	 *
	 * @return object
	 */
	public static function build(array $data);
}
