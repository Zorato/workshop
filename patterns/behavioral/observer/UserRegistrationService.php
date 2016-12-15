<?php

class UserRegistrationService implements SplSubject
{
    use SubjectTrait;

    private $db;

    public function __construct(PDO $PDO)
    {
        $this->db = $PDO;
    }

    public function register($userData)
    {
        $result = $this->db
            ->prepare("INSERT INTO `users` VALUES (".implode(',', array_fill(0, count($userData), '?')).");")
            ->execute(array_values($userData));
        if ($result) {
            $this->notify();
        }
    }

}