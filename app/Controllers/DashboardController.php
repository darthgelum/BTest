<?php


class DashboardController extends AuthController
{
    public function show()
    {
        $params = [
            'name'=>$this->user->name,
            'surname'=>$this->user->surname,
            'balance'=>100
        ];
        $this->app->Response('user',$params);
    }
}