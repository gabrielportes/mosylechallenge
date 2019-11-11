<?php
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
            $iduser = $this->body['iduser'];
            $token = $this->header['token'];

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
        } catch (Exception $e) {
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
            $email = $this->body['email'];
            $name = $this->body['name'];
            $password = $this->body['password'];

            $response = (new Users($email, $name, $password))->create();

            if ($response) {
                $this->response(200, 'Success');
            }
        } catch (Exception $e) {
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
            $iduser = $this->body['iduser'];
            $token = $this->header['token'];

            if (empty($token)) {
                throw new Exception('Token is necessary', 403);
            }

            $users = new Users();

            if (!$users->findByToken($token)) {
                throw new Exception('Invalid token', 401);
            }

            if (empty($iduser)) {
                throw new Exception('Iduser is necesary', 403);
            }

            $user = $users->findById($iduser, true);

            if ($user) {
                if ($token != $user['token']) {
                    throw new Exception('Token does not belong to user', 403);
                }

                $parameters = [];
                if (isset($this->body['name'])) {
                    $parameters['name'] = $this->body['name'];
                }

                if (isset($this->body['email'])) {
                    if ($users->emailExists($this->body['email'])) {
                        throw new Exception("Email already taken.", 500);
                    }

                    $parameters['email'] = $this->body['email'];
                }

                if (isset($this->body['password'])) {
                    $parameters['password'] = $this->body['password'];
                }

                $users->update($iduser, $parameters);

                $userUpdated = $users->findById($iduser, true);

                $this->response(200, 'Success', $userUpdated);
            }
        } catch (Exception $e) {
            $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function usersDelete(): void
    {
        echo 'usersDelete';
    }

    /**
     * Sign in to the system
     *
     * @return void
     */
    public function loginPost(): void
    {
        try {
            $email = $this->body['email'];
            $password = $this->body['password'];

            $response = (new Users())->login($email, $password);

            if ($response) {
                $this->response(200, 'Success', $response);
            }
        } catch (Exception $e) {
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
