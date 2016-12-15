<?php

class MemberCounter implements SplObserver
{

    private $cache;
    private $db;

    public function __construct(Cache $cache, PDO $db)
    {
        $this->cache = $cache;
        $this->db = $db;
    }

    public function update(SplSubject $subject)
    {
        $memberCount = $this->db->query("SELECT COUNT(*) from `users`;")->fetchColumn();
        $this->cache->set('member_count', $memberCount);
    }
}