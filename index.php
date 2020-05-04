<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

$dir = "./app/";
$dh  = opendir($dir);
$dir_list = array($dir);
while (false !== ($filename = readdir($dh))) {
    if($filename!="."&&$filename!=".."&&is_dir($dir.$filename))
        array_push($dir_list, $dir.$filename."/");
}
foreach ($dir_list as $dir) {
    foreach (glob($dir."*.php") as $filename)
        require_once $filename;
}
$app->listen();