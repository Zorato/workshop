<?php

class RedisCache implements Cache
{
    /**
     * @var Redis
     */
    private $redis;

    /**
     * RedisCache constructor.
     *
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function has($key)
    {
        return $this->redis->exists($key);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value)
    {
        return $this->redis->set($key, $value);
    }

}