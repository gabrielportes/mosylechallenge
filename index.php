<?php
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: application/json; charset=utf-8');

if (isset($_REQUEST['url'])) {
    $uri = array_filter(
        explode('/', $_REQUEST['url']),
        function ($elem) {
            return !empty($elem);
        }
    );

    $entity = array_shift($uri);
    $action = array_shift($uri) ?? 'index';

    require_once("{$entity}/{$action}.php");
}
