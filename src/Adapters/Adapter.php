<?php

namespace AOndra\SlickText\Adapters;

use AOndra\SlickText\Concerns\ParsesDates;

abstract class Adapter implements AdapterInterface
{
	use ParsesDates;
}
