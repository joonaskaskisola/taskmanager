<?php

namespace Taskio\Service;

use Taskio\Utils\MemcachedWrapper;

class Cache
{
	/**
	 * @var MemcachedWrapper
	 */
	private $cacheBackend = null;

	/**
	 * Cache constructor.
	 * @param MemcachedWrapper $memcache
	 */
	public function __construct(MemcachedWrapper $memcache)
	{
		$this->cacheBackend = $memcache;
	}

	/**
	 * @param string $key
	 * @param \Closure $closure
	 * @param bool $skipCheck
	 * @return mixed|string
	 */
	public function cache(string $key, \Closure $closure, $skipCheck = false)
	{
		if ($skipCheck) {
			return $this->cacheItem($key, $closure);
		}

		$unserialized = unserialize($this->cacheBackend->get($key));

		if ($unserialized !== false) {
			return $unserialized;
		}

		return $this->cacheItem($key, $closure);
	}

	/**
	 * @param string $key
	 * @param \Closure $closure
	 * @return mixed
	 */
	private function cacheItem($key, \Closure $closure)
	{
		$generated = $closure();
		$this->cacheBackend->set($key, serialize($generated));

		return $generated;
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function clear($key)
	{
		return $this->cacheBackend->delete($key);
	}
}
