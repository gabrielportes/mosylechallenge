<?php
error_reporting(E_ALL ^ E_NOTICE);
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
        $this->password = $this->passwordToHash($password);
    }

    /**
     * Creates a new user in the system
     *
     * @return bool
     */
    public function create(): bool
    {
        if ($this->emailExists($this->email)) {
            throw new Exception('Email already taken.', 500);
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

    /**
     * Generates and sets a token
     *
     * @return void
     */
    private function setToken()
    {
        $this->token = $this->generateToken();
    }

    /**
     * Generates a sha1 hash token for a user, so it can be used to autentication while using API requests
     *
     * @return string
     */
    private function generateToken(): string
    {
        return sha1("{$this->email}{$this->password}{$this->salt}");
    }

    /**
     * Find all users
     *
     * @return array
     */
    public function findAll(): array
    {
        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter` 
        FROM 
            `users`;";

        $result = (new Connection())->queryFetch($query);

        return is_array($result) ? $result : [];
    }

    /**
     * Find a user by his id 
     *
     * @param  int  $iduser
     * @param  bool $showToken
     *
     * @return array
     */
    public function findById(int $iduser, bool $showToken = false): array
    {
        $columns = '';
        if ($showToken) {
            $columns = ',`token`';
        }

        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter`{$columns}
        FROM 
            `users` 
        WHERE 
            `iduser` = {$iduser};";

        $result = (new Connection())->queryFetch($query);
        $result = reset($result);

        if (!$result) {
            throw new Exception('User not found', 404);
        }

        return $result;
    }

    /**
     * Find a user by his token
     *
     * @param  string $token
     *
     * @return array
     */
    public function findByToken(string $token): array
    {
        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter`,
            `token`
        FROM 
            `users` 
        WHERE 
            `token` = '{$token}';";

        $result = (new Connection())->queryFetch($query);
        $result = reset($result);

        return is_array($result) ? $result : [];
    }

    /**
     * Find a user by his email
     *
     * @param  string $email
     *
     * @return array
     */
    public function findByEmail(string $email): array
    {
        $this->isEmailFormatValid($email);

        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter` 
        FROM 
            `users` 
        WHERE 
            `email` = '{$email}';";

        $result = (new Connection())->queryFetch($query);
        $result = reset($result);

        return is_array($result) ? $result : [];
    }

    /**
     * Sign in to the system and returns the user data
     *
     * @param  string $email
     * @param  string $password
     *
     * @return array
     */
    public function login(string $email, string $password): array
    {
        $this->isEmailFormatValid($email);

        if (!$this->emailExists($email)) {
            throw new Exception('User not found', 404);
        }

        $password = $this->passwordToHash($password);

        $query = "SELECT 
            `iduser`,
            `name`,
            `email`,
            `drink_counter`, 
            `token`
        FROM 
            `users` 
        WHERE 
            `email` = '{$email}'
            AND `password` = '{$password}';";

        $result = (new Connection())->queryFetch($query);
        $result = reset($result);

        if (!is_array($result)) {
            throw new Exception('Invalid password', 401);
        }

        return $result;
    }

    /**
     * Check if the email format is valid
     *
     * @param  string $email
     * @param  bool $throwable
     *
     * @return bool
     */
    public function isEmailFormatValid(string $email, bool $throwable = true): bool
    {
        if ($throwable && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address format.', 500);
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Converts a password to a sha1 hash
     *
     * @param  string $password
     *
     * @return string
     */
    private function passwordToHash(string $password): string
    {
        return sha1($password);
    }

    /**
     * Update users informations
     *
     * @param  int   $iduser
     * @param  array $parameters
     *
     * @return bool
     */
    public function update(int $iduser, array $parameters): bool
    {
        $needNewToken = false;
        $update = '';
        foreach ($parameters as $key => $value) {
            if ($key == 'password') {
                $this->password = $this->passwordToHash($value);
                $value = $this->password;
                $needNewToken = true;
            } else if ($key == 'email') {
                $needNewToken = true;
            }

            $update .= "`{$key}` = '{$value}', ";
        }

        if ($needNewToken) {
            $update .= "`token` = '{$this->generateToken()}', ";
        }

        $update = substr_replace($update, ' ', -2);

        $query = "UPDATE
            `users`
        SET
            {$update}
        WHERE 
            `iduser` = {$iduser};";

        return (new Connection())->queryExecute($query);
    }

    /**
     * Set parameters to use on Put action
     *
     * @param  array $body
     *
     * @return array
     */
    public function setParameters(array $body): array
    {
        $parameters = [];
        if (isset($body['name'])) {
            $parameters['name'] = $body['name'];
        }

        if (isset($body['email'])) {
            if ($this->emailExists($body['email'])) {
                throw new Exception('Email already taken.', 500);
            }

            $parameters['email'] = $body['email'];
        }

        if (isset($body['password'])) {
            $parameters['password'] = $body['password'];
        }

        return $parameters;
    }

    /**
     * Check if token belongs to a user
     *
     * @param  string $token
     * @param  int    $iduser
     *
     * @return bool
     */
    public function isTokenFromUser(string $token, int $iduser): bool
    {
        $user = $this->findById($iduser, true);

        if (!$this->findByToken($token)) {
            throw new Exception('Invalid token', 401);
        }

        if ($token != $user['token']) {
            throw new Exception('Token does not belong to user', 403);
        }


        return true;
    }

    /**
     * Deletes a user from the system
     *
     * @param  int $iduser
     *
     * @return bool
     */
    public function delete(int $iduser): bool
    {
        $query = "DELETE
        FROM
            `users`
        WHERE 
            `iduser` = {$iduser};";

        return (new Connection())->queryExecute($query);
    }
}
