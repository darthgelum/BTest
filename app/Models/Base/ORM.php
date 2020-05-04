<?php


class ORM
{
    /** @var FrameWork */
    public $app;
    public function __construct()
    {
        global $app;
        $this->app = $app;
    }
}