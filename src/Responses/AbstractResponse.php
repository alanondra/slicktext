<?php

namespace AOndra\SlickText\Responses;

use Throwable;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;
use AOndra\SlickText\Contracts\ResponseInterface;
use AOndra\SlickText\Exceptions\InvalidResponseException;

/**
 * @property-read \Psr\Http\Message\ResponseInterface $original Original HTTP Response
 */
abstract class AbstractResponse implements ResponseInterface
{
	/**
	 * Original HTTP Response
	 *
	 * @var \Psr\Http\Message\ResponseInterface
	 */
	protected $original;

	/**
	 * Response meta data, or null if not available.
	 *
	 * @var object
	 */
	protected $meta = null;

	/**
	 * Response link information, or null if not available.
	 *
	 * @var object
	 */
	protected $links = null;

	/**
	 * Response error message, or null if not available.
	 *
	 * @var string
	 */
	protected $error = null;

	/**
	 * Construct an instance of a Response.
	 *
	 * @param \Psr\Http\Message\ResponseInterface $response
	 */
	public function __construct(HttpResponseInterface $response)
	{
		$this->original = $response;

		$this->parse($this->getContent());
	}

	/**
	 * Parse the Response content.
	 *
	 * @param string $content
	 *
	 * @return $this
	 */
	protected function parse(string $content)
	{
		try {
			$data = json_decode($content, true);

			if (empty($data)) {
				throw new InvalidResponseException(sprintf('Failed to parse %s response body.', get_called_class()));
			}

			if (key_exists('meta', $data)) {
				$this->meta = (object) $data['meta'];
			}
			if (key_exists('links', $data)) {
				$this->links = (object) $data['links'];
			}
			if (key_exists('error', $data)) {
				$this->error = strval($data['error']);
			}

			$this->handle($data);
		} catch (Throwable $exc) {
			$this->error = $exc->getMessage();
		}

		return $this;
	}

	/**
	 * Handle the parsed Response content.
	 *
	 * @param array $data
	 */
	abstract protected function handle(array $data);

	/**
	 * Get the HTTP Response contents and rewind the body stream's pointer.
	 *
	 * @return string
	 */
	protected function getContent()
	{
		$body = $this->original->getBody();

		$contents = $body->getContents();

		$body->rewind();

		return $contents;
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
