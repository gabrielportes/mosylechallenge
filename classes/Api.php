<?php
header('Content-Type: application/json; charset=utf-8');

require_once('Users.php');

class Api
{
    private $request;
    private $class;
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

    public function usersPost()
    {
        try {
            $email = $this->body['email'];
            $name = $this->body['name'];
            $password = $this->body['password'];

            $response = (new Users($email, $name, $password))->create();

            if ($response) {
                echo $this->response(200, 'Success');
            }
        } catch (Exception $e) {
            echo $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function usersPut()
    {
        echo 'usersPut';
    }

    public function usersDelete()
    {
        echo 'usersDelete';
    }

    public function loginPost()
    {
        echo 'loginPost';
    }

    public function response(int $status, string $msg = '', array $data = []): string
    {
        return json_encode([
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}
