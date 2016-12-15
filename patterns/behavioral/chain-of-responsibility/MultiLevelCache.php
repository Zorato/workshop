<?php

class MultiLevelCache implements Cache
{

    /**
     * @var Cache
     */
    private $slower;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * CacheLevel constructor.
     *
     * @param Cache $fast
     * @param Cache $slow
     */
    public function __construct(Cache $fast, Cache $slow = null)
    {
        $this->cache = $fast;
        if ($slow) {
            $this->slower = $slow;
        }
    }

    /**
     * @param Cache $slower
     * @return $this
     */
    public function setSlower(Cache $slower)
    {
        $this->slower = $slower;
        return $this;
    }

    final public function get($key)
    {
        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        if ($this->slower) {
            return $this->slower->get($key);
        }

        return null;
    }

    final public function set($key, $value)
    {
        return $this->cache->set($key, $value)
               && (
                   ($this->slower && $this->slower->set($key, $value))
                   || empty($this->slower)
               );
    }

    final public function has($key)
    {
        return $this->cache->has($key) || ($this->slower && $this->slower->has($key));
    }

}