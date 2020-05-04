<?php
//private function defineConstants(){
//    define('',__DIR__);
//    define('CONTROLLERS_ROUTE',APP_DIR.'/controllers/');
//    define('VIEWS_ROUTE',APP_DIR.'/views/');
//    define('TRANSLATION_DIR',APP_DIR.'/translations/');
//    define('DB_HOST','127.0.0.1');
//    define('DB_USER','root');
//    define('DB_PASSWORD','');
//    define('DB_DATABASE','');
//    define('APP_NAME','');
//}
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}
require_once('FrameWork.php');


//load Framework
$conf = new FWConfig();

require_once('config.php');

$app=new FrameWork($conf);

require_once('app/web.php');
$app->listen();