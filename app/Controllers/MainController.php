<?php


class MainController extends Controller
{
    public function run()
    {
        $login = $this->app->getRequest()->request["login"];
        $password = $this->app->getRequest()->request["password"];
        if(!$login || !$password || !$this->app->IsPost())
        {
            $this->app->Redirect("/");
        }

    }
}