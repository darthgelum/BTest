<?php
namespace App\Controllers\Base;
use Exception;
use FrameWork\Kernel;

abstract class Controller
{
    /** @var Kernel */
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