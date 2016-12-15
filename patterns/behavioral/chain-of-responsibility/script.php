<?php

$cache = new MultiLevelCache(
    new RedisCache($redis), // $redis represents Redis driver/connection implementation
    new MultiLevelCache(
        new FileCache('cache'),
        new FileCache('/some/mounted/remote/folder')
    )
);

echo $cache->has('key') ? $cache->get('key') : (int) $cache->set('key', 'value');