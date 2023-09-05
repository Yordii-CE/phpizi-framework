<?php

namespace Framework\Databases;

use Framework\Definitions\Interfaces\IDatabase;

class SqlSvrDatabase implements IDatabase
{
    protected $host;
    protected $db_name;
    protected $user;
    protected $password;
    protected $charset;


    public function connect()
    {
        try {
            $connection = "sqlsrv:Server={$this->host};Database={$this->db_name}";
            $pdo = new \PDO($connection, $this->user, $this->password);
            return $pdo;
        } catch (\PDOException $e) {
            die('Connection error' . $e->getMessage());
        }
    }
}
