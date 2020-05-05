<?php
require_once "app/Models/User.php";

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
        $user = (new User())->getByLogin($login);
        if($user->pass_hash === md5($password)) {
            AuthController::set($user);
            $this->app->Redirect("/dash");
        }
    }
}