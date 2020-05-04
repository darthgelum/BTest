<?php

require_once "Controllers/Base/Controller.php";
require_once "Controllers/Base/AuthController.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/LoginController.php";

$app->get('/',function() use ($app){
    $app->Response('main');
});

$app->get('/login',function(){
     new LoginController();
});

$app->get('/dash',function(){
    new DashboardController("show");
});