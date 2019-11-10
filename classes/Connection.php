<?php

class Connection
{

    private $host = 'localhost';
    private $port = 3306;
    private $user = 'root';
    private $password = '';
    private $dbname = 'mosylechallenge';
    private $PDO;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host}:{$this->port};dbname={$this->dbname}";

        try {
            $this->PDO = new PDO($dsn, $this->user, $this->password);
        } catch (Exception $e) {
            $this->createSchema();
        }
    }

    /**
     * Create Schema `mosylechallenge` if not exists
     *
     * @return bool
     */
    private function createSchema(): bool
    {
        try {
            $dsn = "mysql:host={$this->host}:{$this->port};";
            $this->PDO = new PDO($dsn, $this->user, $this->password);
            $schema = file_get_contents('schema.sql');
            $this->queryExecute($schema);

            return $this->queryExecute('USE `mosylechallenge`;');
        } catch (Exception $e) {
            throw new Exception("Database connection failed: '{$e->getMessage()}'");
        }
    }

    /**
     * Executes a query. Returns TRUE on success or FALSE on failure.
     *
     * @param  string $query
     *
     * @return bool
     */
    public function queryExecute(string $query): bool
    {
        if (!$this->PDO->inTransaction()) {
            $this->PDO->beginTransaction();
        }

        try {
            $sth = $this->PDO->prepare($query);

            if (!$sth->execute()) {
                throw new Exception("Invalid query.");
            }

            $this->PDO->commit();

            return true;
        } catch (Exception $e) {
            $this->PDO->rollBack();
            throw new Exception("Query failed to execute: '{$e->getMessage()}'");
        }
    }

    /**
     * Executes a query. Returns an array of arrays indexed by column name as returned in your result set
     *
     * @param  string $query
     *
     * @return array
     */
    public function queryFetch(string $query): array
    {
        if (!$this->PDO->inTransaction()) {
            $this->PDO->beginTransaction();
        }

        try {
            $sth = $this->PDO->prepare($query);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->PDO->commit();

            return $result;
        } catch (Exception $e) {
            $this->PDO->rollBack();
            throw new Exception("Query failed to fetch: '{$e->getMessage()}'");
        }
    }
}
