<?php

namespace AOndra\SlickText;

use Psr\Log\LoggerInterface;
use Psr\EventDispatcher\{
	EventDispatcherInterface,
	StoppableEventInterface
};
use GuzzleHttp\Client as HttpClient;
use AOndra\SlickText\Factories\Http\ClientFactory;
use AOndra\SlickText\Repositories\{
	AccountRepository
};

/**
 * @property-read \AOndra\SlickText\Repositories\AccountRepository $account Account Repository
 */
class Client
{
	/**
	 * Base URL for All Requests
	 *
	 * @var string
	 */
	protected static $url = 'https://api.slicktext.com/v1/';

	/**
	 * Create an instance of a SlickText Client.
	 *
	 * @param string $publicKey
	 * @param string $privateKey
	 * @param array $options
	 *
	 * @return \static
	 */
	public static function create(string $publicKey, string $privateKey, array $options = [])
	{
		$http = ClientFactory::createClient(static::$url, $publicKey, $privateKey, $options);

		return new static($http);
	}

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
	 * Mapping of properties to repository types.
	 *
	 * @var array
	 */
	protected $repositories = [
		'account' => AccountRepository::class,
	];

	/**
	 * Construct an instance of a SlickText Client.
	 *
	 * @param \GuzzleHttp\Client $http
	 */
	public function __construct(HttpClient $http)
	{
		$this->http = $http;

		foreach ($this->repositories as $name => $className) {
			$this->repositories[$name] = new $className($this->http);
		}
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
		$value = $this->__get($property);

		return !empty($value);
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
		if (key_exists($property, $this->repositories)) {
			return $this->repositories[$property];
		}

		return null;
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
