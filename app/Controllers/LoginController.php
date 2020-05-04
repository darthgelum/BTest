<?php


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
        Session::write('auth_id',"1");
        $this->app->Redirect("/dash");
    }
}