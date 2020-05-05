<?php

use FrameWork\FWConfig;
use FrameWork\Kernel;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}
require "vendor/autoload.php";
$conf = new FWConfig();

require_once('config.php');
global $app;
$app =new Kernel($conf);

require_once "app/web.php";
$app->listen();