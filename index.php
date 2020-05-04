<?php

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}
require_once('FrameWork/buildFw.php');


//load Framework
$conf = new FWConfig();

require_once('config.php');
$app=new FrameWork($conf);
require_once "./app/web.php";

$app->listen();