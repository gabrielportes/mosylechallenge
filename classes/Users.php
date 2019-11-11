<?php
require_once('Connection.php');

class Users
{
    private $name;
    private $drink_counter = 0;
    private $email;
    private $password;
    private $token;
    private $salt = 'mosyle';

    public function __construct(string $email = '', string $name = '', string $password = '')
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = sha1($password);
    }

    public function create(): bool
    {
        if ($this->emailExists($this->email)) {
            throw new Exception("Email already taken.", 500);
        }

        $this->setToken();

        $query = "INSERT INTO `users` ( 
            `name`,
            `drink_counter`,
            `email`,
            `password`,
            `token`
        ) VALUES (
            '{$this->name}',
            '{$this->drink_counter}',
            '{$this->email}',
            '{$this->password}',
            '{$this->token}'
        );";

        return (new Connection())->queryExecute($query, true);
    }

    /**
     * Check if an email address already exists
     *
     * @param  string $email
     *
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return boolval($this->findByEmail($email));
    }

    private function setToken()
    {
        $this->token = $this->generateToken();
    }

    private function generateToken(): string
    {
        return sha1("{$this->email}{$this->password}{$this->salt}");
    }

    public function findAll(): array
    {
        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter` 
        FROM `users`;";

        $result = (new Connection())->queryFetch($query);

        return is_array($result) ? $result : [];
    }

    public function findById(int $iduser): array
    {
        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter` 
        FROM `users` 
        WHERE `iduser` = '{$iduser}';";

        $result = (new Connection())->queryFetch($query);
        $result = reset($result);

        return is_array($result) ? $result : [];
    }

    public function findByEmail(string $email): array
    {
        if (!$this->isEmailFormatValid($email)) {
            throw new Exception('Invalid email address format.', 500);
        }

        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter` 
        FROM `users` 
        WHERE `email` = '{$email}';";

        $result = (new Connection())->queryFetch($query);
        $result = reset($result);

        return is_array($result) ? $result : [];
    }

    private function isEmailFormatValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}
