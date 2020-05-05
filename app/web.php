<?php

use App\Controllers\Base\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\LoginController;

$app->get('/',function() use ($app){
    if(AuthController::getSessionId())
    {
        $app->Redirect("/dash");
    }
    $app->Response('main');
});

$app->get('/login',function(){
     new LoginController();
});

$app->get('/dash',function(){
    new DashboardController("show");
});

$app->get('/drop_money',function(){
    new DashboardController("drop");
});