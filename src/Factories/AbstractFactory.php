<?php

namespace AOndra\SlickText\Factories;

use AOndra\SlickText\Concerns\ParsesDates;
use AOndra\SlickText\Contracts\FactoryInterface;

abstract class AbstractFactory implements FactoryInterface
{
	use ParsesDates;
}
