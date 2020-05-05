<?php


class DashboardController extends AuthController
{
    public function show()
    {
        $balance = (new UsersRepository())->getBalanceByUserId($this->user->id);
        $params = [
            'name'=>$this->user->name,
            'surname'=>$this->user->surname,
            'balance'=>$balance->money
        ];
        $this->app->Response('user',$params);
    }
}