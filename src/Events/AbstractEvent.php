<?php

namespace AOndra\SlickText\Events;

use Carbon\Carbon;
use Psr\EventDispatcher\StoppableEventInterface;

abstract class AbstractEvent implements StoppableEventInterface
{
	/**
	 * Event timestamp.
	 *
	 * @var \Carbon\Carbon
	 */
	protected $timestamp;

	/**
	 * Indicator for if Event propogation is stopped.
	 *
	 * @var boolean
	 */
	protected $stopped = false;

	/**
	 * Construct an Event.
	 *
	 * @param \Carbon\Carbon $timestamp
	 */
	public function __construct(Carbon $timestamp)
	{
		$this->timestamp = $timestamp;
	}

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

	/**
	 * Magic method to handle accessing inaccessible properties.
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		return (property_exists($this, $property))
			? $this->$property
			: null;
	}

	/**
	 * Magic method to handle isset or empty calls on inaccessible properties.
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __isset($property)
	{
		return isset($this->$property);
	}
}
