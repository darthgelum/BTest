<?php

class AuthController extends Controller
{
    public $user;
    public function __construct($action = "run")
    {
        global $app;
        $this->app = $app;
        if($this->getSessionId()) {
            $this->user = (new User())->getById($this->getSessionId());
            parent::__construct($action);
        }
        else
        {
            $this->app->Redirect("/");
        }
    }

    public function authenticate()
    {

    }

    public static function set(User $user)
    {
        Session::write('auth_id',$user->id);
    }

    public static function getSessionId()
    {
        $auth_id = Session::read("auth_id");
        return $auth_id;
    }
}