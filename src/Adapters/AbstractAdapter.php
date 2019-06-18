<?php

namespace AOndra\SlickText\Adapters;

use AOndra\SlickText\Concerns\ParsesDates;
use AOndra\SlickText\Contracts\AdapterInterface;

abstract class AbstractAdapter implements AdapterInterface
{
	use ParsesDates;
}
