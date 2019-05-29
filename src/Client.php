<?php

namespace AOndra\SlickText;

use Psr\Log\LoggerInterface;
use Psr\EventDispatcher\{
	EventDispatcherInterface,
	StoppableEventInterface
};
use GuzzleHttp\Client as HttpClient;

class Client
{
	/**
	 * Base URL for All Requests
	 *
	 * @var string
	 */
	protected static $url = 'https://api.slicktext.com/v1/';

	/**
	 * Guzzle HTTP Client
	 *
	 * @var \GuzzleHttp\Client
	 */
	protected $http;

	/**
	 * Logger
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Event Dispatcher
	 *
	 * @var \Psr\EventDispatcher\EventDispatcherInterface
	 */
	protected $dispatcher;

	/**
	 * Construct an instance of a SlickText Client.
	 *
	 * @param \GuzzleHttp\Client $http
	 */
	public function __construct(HttpClient $http)
	{
		$this->http = $http;
	}

	/**
	 * Set the Logger.
	 *
	 * @param \Psr\Log\LoggerInterface $logger
	 *
	 * @return $this
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;

		return $this;
	}

	/**
	 * Set the EventDispatcher.
	 *
	 * @param \Psr\EventDispatcher\EventDispatcherInterface $dispatcher
	 *
	 * @return $this
	 */
	public function setEventDispatcher(EventDispatcherInterface $dispatcher)
	{
		$this->dispatcher = $dispatcher;

		return $this;
	}

	/**
	 * Wrapper for call to log method on Logger instance.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 *
	 * @return $this
	 */
	protected function log($level, string $message, array $context = [])
	{
		if (!empty($this->logger)) {
			$this->logger->log($level, $message, $context);
		}

		return $this;
	}

	/**
	 * Wrapper for call to log method on EventDispatcher instance.
	 *
	 * @param \Psr\EventDispatcher\StoppableEventInterface $event
	 *
	 * @return $this
	 */
	protected function dispatch(StoppableEventInterface $event)
	{
		if (!empty($this->dispatcher)) {
			$this->dispatcher->dispatch($event);
		}

		return $this;
	}
}
