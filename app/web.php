<?php

$app->get('/{name}',function($name) use ($app){//Action
    $app->Response("main",array('name'=>$name));
});