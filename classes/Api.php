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

    public function usersGet()
    {
        echo 'usersGet';
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

    public function usersPut(): void
    {
        echo 'usersPut';
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
