<?php

namespace AOndra\SlickText\Contracts;

interface AdapterInterface
{
	/**
	 * Transform the given data to the expected format.
	 *
	 * @param mixed $data
	 *
	 * @return mixed
	 */
	public function transform($data);
}
