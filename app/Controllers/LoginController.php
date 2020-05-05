<?php

namespace App\Controllers;
use App\Controllers\Base\AuthController;
use App\Controllers\Base\Controller;
use App\Repositories\UsersRepository;

class LoginController extends Controller
{
    public function run()
    {
        $login = $this->app->getRequest()->request["login"];
        $password = $this->app->getRequest()->request["password"];

        if(!$login || !$password || !$this->app->IsPost())
        {
            $this->app->Redirect("/");
        }
        $user = (new UsersRepository())->getByLogin($login);
        if($user->pass_hash === md5($password)) {
            AuthController::set($user);
            $this->app->Redirect("/dash");
        }
    }
}
