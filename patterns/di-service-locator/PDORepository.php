<?php

/**
 * Class PDORepository
 */
class PDORepository implements Repository
{

    /**
     * @var Connection
     */
    private $pdo;

    /**
     * PDORepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->pdo = $connection;
    }

    public function save($data)
    {
        return $this->pdo->getConnection()
            ->prepare("INSERT INTO `table` VALUES (?, ?);")
            ->execute($data);
    }

    public function get($id)
    {
        $statement = $this->pdo->getConnection()->prepare("SELECT * FROM `table` WHERE `id` = :id;");
        $statement->bindValue('id', $id);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }



}