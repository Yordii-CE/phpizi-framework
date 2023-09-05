<?php

namespace Framework\Databases;

use Framework\Definitions\Interfaces\IDatabase;

class MysqlDatabase implements IDatabase
{
    protected $host;
    protected $db_name;
    protected $user;
    protected $password;
    protected $charset;


    public function connect()
    {
        try {
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                \PDO::ATTR_ERRMODE   => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new \PDO($connection, $this->user, $this->password, $options);
            return $pdo;
        } catch (\PDOException $e) {
            die('Connection error' . $e->getMessage());
        }
    }
}
