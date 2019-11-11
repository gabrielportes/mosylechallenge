<?php
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
