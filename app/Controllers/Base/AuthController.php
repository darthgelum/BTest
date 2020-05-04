<?php

class AuthController extends Controller
{
    public $user;
    public function __construct($action = "run")
    {
        if(Session::read("auth_id")) {
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
}