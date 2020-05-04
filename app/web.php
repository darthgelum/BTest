<?php

$app->get('/',function() use ($app){
    $app->Response('main');
    echo "aa";
});

$app->get('/login',function() use ($app){
    $login = $app->getRequest()->request["login"];
    $password = $app->getRequest()->request["password"];
    echo "Welcome {$login} with password {$password}";
});