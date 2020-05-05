<?php

abstract class AuthController extends Controller
{
    public $user;
    public function __construct($action = "run")
    {
        global $app;
        $this->app = $app;
        if($this->getSessionId()) {
            $this->authenticate();
            parent::__construct($action);
        }
        else
        {
            $this->app->Redirect("/");
        }
    }

    public function authenticate()
    {
        $this->user = (new UsersRepository())->getById($this->getSessionId());
    }

    public static function set(User $user)
    {
        Session::write('auth_id',$user->id);
    }

    public static function getSessionId()
    {
        return Session::read("auth_id");
    }
}