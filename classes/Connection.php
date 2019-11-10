<?php

class Connection
{

    private $host = 'localhost';
    private $port = 3306;
    private $user = 'root';
    private $password = '';
    private $dbname = 'mosylechallenge';
    private $conn;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host}:{$this->port};dbname={$this->dbname}";
        try {
            $this->conn = new PDO($dsn, $this->user, $this->password);
        } catch (Exception $e) {
            $this->createSchema();
        }
    }

    private function createSchema(): bool
    {
        try {
            $dsn = "mysql:host={$this->host}:{$this->port};";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $schema = file_get_contents('schema.sql');
            $this->queryExec($schema);

            return $this->queryExec('USE `mosylechallenge;`');
        } catch (Exception $e) {
            throw new Exception("Database connection failed: '{$e->getMessage()}'");
        }
    }

    private function queryExec(string $query): bool
    {
        $this->conn->beginTransaction();

        try {
            $sth = $this->conn->prepare($query);
            return $sth->execute();
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Query failed to execute: '{$e->getMessage()}'");
        }
    }
}
