<?php


class Controller
{
    /** @var FrameWork */
    public $app;
    public function __construct(FrameWork $app)
    {
        $this->app = $app;
    }

    /**
     * Method for proceeding requests
     * @return String
     * @throws Exception
     */
    public function run()
    {
        throw new Exception("Method run() not implemented");
    }
}