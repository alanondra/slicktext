<?php

namespace AOndra\SlickText\Events;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class Event implements StoppableEventInterface
{
	/**
	 * Indicator for if Event propogation is stopped.
	 *
	 * @var boolean
	 */
	protected $stopped = false;

	/**
	 * Is propagation stopped?
	 *
	 * This will typically only be used by the Dispatcher to determine if the
	 * previous listener halted propagation.
	 *
	 * @return bool
	 *   True if the Event is complete and no further listeners should be called.
	 *   False to continue calling listeners.
	 */
	public function isPropagationStopped() : bool
	{
		return $this->stopped;
	}

	/**
	 * Stop Event propagation.
	 *
	 * @return $this
	 */
	public function stop()
	{
		$this->stopped = true;

		return $this;
	}

	/**
	 * Resume Event propagation.
	 *
	 * @return $this
	 */
	public function resume()
	{
		$this->stopped = false;

		return $this;
	}
}
