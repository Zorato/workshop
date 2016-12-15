<?php

/**
 * Class Service
 */
class Service
{
    /**
     * @var Repository
     */
    private $repo;

    public function __construct(Container $container)
    {
        $this->repo = $container->make('Repository');
    }

    public function act($postData)
    {
        $this->validate($postData);
        return $this->repo->save($postData);
    }

    private function validate($data)
    {
        // some validation logic
    }

}