<?php

namespace AOndra\SlickText\Adapters;

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
