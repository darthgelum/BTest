<?php

class View
{
    protected $data;
    /** @var FrameWork */
    protected $framework;
    protected $src;

    public function __construct($src,array $vars,$framework = null){
        $this->data = $vars;
        $this->framework = $framework;
        $this->src = $src;
    }

    /**
     * Renders a view
     * @throws Exception if View not found
     */
    public  function load(){
        $app = $this->framework;
        $data = $this->data;
        $var = function ($name) use ($data) {return $data[$name];};
        if(file_exists($this->src))
            include_once($this->src); //scoped to this class
        else{
            if($app && !$app->Production())
                throw new Exception(" View filename: {$this->src} NOT found");
        }
    }
}