<?php

namespace AOndra\SlickText;

use Throwable;
use RuntimeException;
use Psr\Log\{
	LogLevel,
	LoggerInterface
};
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use function GuzzleHttp\Psr7\stream_for;
use AOndra\SlickText\Exceptions\{
	InvalidContentException,
	InvalidEventException,
	InvalidMethodException,
	TokenMismatchException
};

class Webhook
{
	/**
	 * Event Dispatcher
	 *
	 * @var \Psr\EventDispatcher\EventDispatcherInterface
	 */
	protected $dispatcher;

	/**
	 * Logger
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * SlickText Webhook Secret Key
	 *
	 * @var string
	 */
	protected $secretKey;

	/**
	 * Mapping of event names to event factories
	 *
	 * @var array
	 */
	protected $events = [
		//
	];

	/**
	 * Construct a Webhook instance.
	 *
	 * @param string $secretKey
	 * @param EventDispatcherInterface $dispatcher
	 */
	public function __construct(string $secretKey, EventDispatcherInterface $dispatcher)
	{
		$this->secretKey = $secretKey;
		$this->dispatcher = $dispatcher;
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
	 * Handle a ServerRequest and dispatch appropriate events.
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 *
	 * @return \Psr\EventDispatcher\StoppableEventInterface
	 */
	public function handle(ServerRequestInterface $request)
	{
		try {
			$data = $this->validate($request);

			$eventName = $data['Event'] ?? null;

			if (empty($eventName)) {
				throw new InvalidEventException('Event name could not be determined');
			}

			if (!key_exists($eventName, $this->events)) {
				throw new InvalidEventException(sprintf('Event %s is not currently supported'));
			}

			$factory = $this->events[$eventName];

			$event = $factory::build($data);

			$this->dispatcher->dispatch($event);

			return $event;
		} catch (RuntimeException $exc) {
			$this->log(LogLevel::CRITICAL, $exc->getMessage(), [
				'code' => $exc->getCode(),
				'file' => $exc->getFile(),
				'line' => $exc->getLine(),
			]);

			throw $exc;
		} catch (Throwable $exc) {
			$this->log(LogLevel::ERROR, $exc->getMessage(), [
				'code' => $exc->getCode(),
				'file' => $exc->getFile(),
				'line' => $exc->getLine(),
			]);

			throw $exc;
		}

		return null;
	}

	/**
	 * Validate and parse the ServerRequest.
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 *
	 * @return array
	 *
	 * @throws \AOndra\SlickText\Exceptions\InvalidMethodException
	 * @throws \AOndra\SlickText\Exceptions\TokenMismatchException
	 */
	protected function validate(ServerRequestInterface &$request)
	{
		$method = $request->getMethod();

		if ($method !== 'POST') {
			throw new InvalidMethodException(sprintf('Expected POST method, received %s', $method));
		}

		$stream = stream_for(strval($request->getBody()));

		$request = $request->withBody($stream);

		$header = 'X-Slicktext-Signature';

		if (!$request->hasHeader($header)) {
			throw new TokenMismatchException('Missing token header');
		}

		$token = $request->getHeaderLine($header);

		$data = $this->parse($request);

		$hash = $this->hash($data);

		if ($token != $hash) {
			throw new TokenMismatchException('Token mismatch');
		}

		return $data;
	}

	/**
	 * Parse the ServerRequest body.
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 *
	 * @return array
	 *
	 * @throws \AOndra\SlickText\Exceptions\InvalidContentException
	 */
	protected function parse(ServerRequestInterface &$request)
	{
		$data = [];

		$type = $request->getHeaderLine('Content-Type');

		$body = strval($request->getBody());

		$request->getBody()->rewind();

		if (stripos($type, 'application/x-www-form-urlencoded') !== false) {
			$form = [];

			parse_str($body, $form);

			if (key_exists('data', $form)) {
				$data = json_decode($form['data'], true);
			}
		} elseif (stripos($type, 'application/json') !== false) {
			$data = json_decode($body, true);
		}

		if (empty($data)) {
			throw new InvalidContentException('Failed to parse request body');
		}

		return $data;
	}

	/**
	 * Generate a hash from the parsed data.
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	protected function hash(array $data)
	{
		return hash_hmac('md5', json_encode($data), $this->secretKey);
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
}
