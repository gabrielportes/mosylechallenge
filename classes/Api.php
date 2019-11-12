<?php
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: application/json; charset=utf-8');
require_once('Users.php');

class Api
{
    private $method;
    private $body;
    private $header;

    public function __construct(string $method = 'GET', array $body = [], array $header = [])
    {
        $this->method = $method;
        $this->body = $body;
        $this->header = $header;
    }

    /**
     * Find for users in the system
     *
     * @return void
     */
    public function usersGet()
    {
        try {
            if (isset($this->body['iduser'])) {
                $iduser = $this->body['iduser'];
            }

            if (isset($this->header['token'])) {
                $token = $this->header['token'];
            }

            if (empty($token)) {
                throw new Exception('Token is necessary', 403);
            }

            $users = new Users();

            if (!$users->findByToken($token)) {
                throw new Exception('Invalid token', 401);
            }

            if (!empty($iduser)) {
                $response = $users->findById($iduser);
            } else {
                $response = $users->findAll();
            }

            if ($response) {
                $this->response(200, 'Success', $response);
            }
        } catch (Throwable $e) {
            $this->response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Sign up a new user to the system
     *
     * @return void
     */
    public function usersPost(): void
    {
        try {
            if (isset($this->body['email'])) {
                $email = $this->body['email'];
            }

            if (isset($this->body['name'])) {
                $name = $this->body['name'];
            }

            if (isset($this->body['password'])) {
                $password = $this->body['password'];
            }

            $response = (new Users($email, $name, $password))->create();

            if ($response) {
                $this->response(201, 'Created successfully');
            }
        } catch (Throwable $e) {
            $this->response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Update users informations by his id
     *
     * @return void
     */
    public function usersPut(): void
    {
        try {
            if (empty($this->body['iduser'])) {
                throw new Exception('Precondition Required: required request body content is missing "iduser"', 428);
            }

            if (empty($this->header['token'])) {
                throw new Exception('Precondition Required: required request header is missing "token"', 428);
            }

            $iduser = $this->body['iduser'];
            $token = $this->header['token'];

            $users = new Users();

            $users->isTokenFromUser($token, $iduser);

            $parameters = $users->setParameters($this->body);

            $users->update($iduser, $parameters);

            $userUpdated = $users->findById($iduser, true);

            $this->response(200, 'Updated successfully', $userUpdated);
        } catch (Throwable $e) {
            $this->response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Deletes a user from the system
     *
     * @return void
     */
    public function usersDelete(): void
    {
        try {
            if (empty($this->body['iduser'])) {
                throw new Exception('Precondition Required: required request body content is missing "iduser"', 428);
            }

            if (empty($this->header['token'])) {
                throw new Exception('Precondition Required: required request header is missing "token"', 428);
            }

            $iduser = $this->body['iduser'];
            $token = $this->header['token'];

            $users = new Users();

            $users->isTokenFromUser($token, $iduser);

            $userDeleted = $users->findById($iduser, true);

            $users->delete($iduser);

            $this->response(200, 'Deleted successfully', $userDeleted);
        } catch (Throwable $e) {
            $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function usersDrink(): void
    {
        try {
            if (empty($this->body['iduser'])) {
                throw new Exception('Precondition Required: required request body content is missing "iduser"', 428);
            }

            if (empty($this->body['drink_ml'])) {
                throw new Exception('Precondition Required: required request body content is missing "drink_ml"', 428);
            }

            if (empty($this->header['token'])) {
                throw new Exception('Precondition Required: required request header is missing "token"', 428);
            }

            $iduser = $this->body['iduser'];
            $drink_ml = $this->body['drink_ml'];
            $token = $this->header['token'];

            $users = new Users();

            $users->isTokenFromUser($token, $iduser);

            $users->drink($iduser, $drink_ml);

            $user = $users->findById($iduser);

            $this->response(200, 'Updated successfully', $user);
        } catch (Throwable $e) {
            $this->response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Sign in to the system
     *
     * @return void
     */
    public function loginPost(): void
    {
        try {
            if (empty($this->body['email'])) {
                throw new Exception('Precondition Required: required request body content is missing "email"', 428);
            }

            if (empty($this->body['password'])) {
                throw new Exception('Precondition Required: required request body content is missing "password"', 428);
            }

            $email = $this->body['email'];
            $password = $this->body['password'];

            $response = (new Users())->login($email, $password);

            if ($response) {
                $this->response(200, 'Success', $response);
            }
        } catch (Throwable $e) {
            $this->response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Sends a response
     *
     * @param  int $status HTTP RESPONSE CODE
     * @param  string $msg
     * @param  array $data
     *
     * @return void
     */
    public function response(int $status, string $msg = '', array $data = []): void
    {
        echo json_encode([
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}
