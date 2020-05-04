<?php

$app->get('/a',function() use ($app){//Action
    echo "a";
});

$app->get('/{name}',function($name) use ($app){//Action
    $app->Response("main",array('name'=>$name));
});

