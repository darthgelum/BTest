<?php

namespace App\Controllers;

use App\Controllers\Base\AuthController;
use App\Repositories\{PaymentsRepository,UsersRepository};


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

    public function drop()
    {
        $sum = $this->app->getRequest()->request["sum"];
        $balance = (new UsersRepository())->getBalanceByUserId($this->user->id)->money;
        if($sum>$balance)
        {
            $this->app->Redirect("/dash");
        }
        if($sum>0)
        {
            $sum=-$sum;
        }
        (new PaymentsRepository())->insertPayment($this->user->id, $sum);
        $this->app->Redirect("/dash");
    }
}