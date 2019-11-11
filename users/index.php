<?php
header('Content-Type: application/json; charset=utf-8');

require_once('../classes/Api.php');

$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
$body = json_decode(file_get_contents('php://input'), 1);
$header = getallheaders();

$uri = array_filter(
    explode('/', $_SERVER['REQUEST_URI']),
    function ($elem) {
        return !empty($elem);
    }
);

$class = array_pop($uri);

$api = new Api($requestMethod, $body, $header);

switch ($requestMethod) {
    case 'GET':
        $api->usersGet();
        break;

    case 'POST':
        $api->usersPost();
        break;

    case 'PUT':
        $api->usersPut();
        break;

    case 'DELETE':
        $api->usersDelete();
        break;

    default:
        $api->response(500, 'Requisition method not implemented');
        break;
}
