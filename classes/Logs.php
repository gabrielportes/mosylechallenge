<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('Connection.php');

class Logs
{
    /**
     * Singleton Class
     *
     * @return void
     */
    private function __construct()
    { }

    /**
     * Saves a log when a user drinks water
     *
     * @param  int $iduser
     * @param  int $drink_ml
     *
     * @return bool
     */
    public static function set(int $iduser, int $drink_ml): bool
    {
        $query = "INSERT INTO `logs` (
            `iduser`,
            `drink_ml`
        )
        VALUES (
            {$iduser},
            {$drink_ml}
        );";

        return (new Connection())->queryExecute($query);
    }

    /**
     * Gets the entire drink history from a user
     *
     * @param  int $iduser
     *
     * @return array
     */
    public static function getUserHistory(int $iduser): array
    {
        $query = "SELECT
            `users`.`iduser`,
            `users`.`name`,
            `users`.`email`,
            `logs`.`drink_ml`,
            `logs`.`date`
        FROM
            `users`
        LEFT JOIN
            `logs`
            ON `users`.`iduser` = `logs`.`iduser`
        WHERE
            `users`.`iduser` = {$iduser};";

        $result = (new Connection())->queryFetch($query);

        return is_array($result) ? $result : [];
    }
}
