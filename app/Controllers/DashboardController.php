<?php


class DashboardController extends AuthController
{
    public function show()
    {
        $this->app->Response('user',['name'=>"A",'surname'=>"Bb",'balance'=>100]);
    }
}