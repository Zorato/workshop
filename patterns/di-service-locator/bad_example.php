<?php

class SmartAssThatCreatesEverythingByItself
{
    protected $db;

    public function run($postData)
    {
        $this->getRepo()->save($postData);
    }

    public function getConnection()
    {
        if (empty($this->db)) {
            $this->db = new DBConnection(Config::get('db'));
        }
        return $this->db;
    }

    public function getRepo()
    {
        return new PDORepository($this->getConnection());
    }
}

class SingletonUtilizer {

    public function run($postData)
    {
        $repo = new PDORepository(DB::connection());
        $repo->save($postData);
    }
}