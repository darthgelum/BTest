<?php
require_once "app/Models/Base/ORM.php";

class Controller
{
    /** @var FrameWork */
    public $app;
    public function __construct($action = "run")
    {
        global $app;
        $this->app = $app;
        $this->$action();
    }

    /**
     * Default method for proceeding requests
     * @throws Exception
     */
    public function run()
    {
        throw new Exception("Method run() not implemented");
    }
}