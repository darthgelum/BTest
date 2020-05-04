<?php

$app->get('/',function() use ($app){
    $app->Response('main');
    echo "aa";
});

$app->get('/login',function() use ($app){
    $controller = new MainController($app);

    echo $controller->run();
});