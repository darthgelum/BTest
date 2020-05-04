<?php
class FWConfig {
    public $prod_env = false;
    public $db_host;
    public $db_user;
    public $db_password;
    public $db_name;


    public $app_dir = __DIR__;
}



class FrameWork{
    /** @var FWConfig **/
    protected $config;

    protected $request;
    protected $db;
    protected $routes = array();

    public function __construct(FWConfig $config){
        $this->config = $config;
        $this->buildRequest();
    }

    public function listen(){
        $slugs = array();
        $run = 0;
        foreach($this->routes as $route)
            if($func = $this->processUri($route,$slugs)){
                //call callback function with params in slugs
                $run = 1;
                call_user_func_array($func,$slugs);
            }

        if(!$run) $this->error('Not route found',1);
    }

    /**
     * @return mysqli
     * @throws Exception
     */
    public function getDB(){
        $this->db = $this->db ? $this->db : new mysqli($this->config->db_host, $this->config->db_user, $this->config->db_password, $this->config->db_name);
        if ($this->db->connect_errno) {
            $this->error("Error connecting to database: ".$this->config->db_name."<br/> Info:". $this->db->connect_error);
        }
        return $this->db;
    }

    private function buildRequest(){
        $this->request = new stdClass();
        $this->request->query = $_GET;
        $this->request->request = $_POST;
        $this->request->server = $_SERVER;
        $this->request->cookie = $_COOKIE;
    }

    /**
     * @param  $uri
     * @param  $function
     */
    public function get($uri, $function){
        $routeKey =  $uri;

        $route = new stdClass();
        $route->route = $routeKey;
        $route->function = $function;

        //save route and function
        $this->routes[] = $route;
    }

    private function processUri($route,&$slugs = array()){
        $uri = isset($this->request->server['REQUEST_URI']) ? $this->request->server['REQUEST_URI'] : '/' ;
        $func = $this->matchUriWithRoute($uri,$route,$slugs);
        return $func ? $func : false;
    }

    private function matchUriWithRoute($uri,$route,&$slugs){
        $uri_segments = preg_split('/[\/]+/',$uri,null,PREG_SPLIT_NO_EMPTY);

        $route_segments = preg_split('/[\/]+/',$route->route,null,PREG_SPLIT_NO_EMPTY);

        if($this->compareSegments($uri_segments,$route_segments,$slugs)){
            //route matched
            return $route->function; //Object route
        }
        return false;
    }

    /**
     * Get request Object
     * @return Request class
     */
    public function getRequest(){
        return $this->request;
    }


    public function Redirect($href){
        header('Location: '.$href);
        exit;
    }

    public function Production()
    {
        return $this->config->prod_env;
    }
    /**
     * Return a new HTTP response.
     * @param string $view_filename Source to the file
     * @param array $vars Data to pass to the View
     * @param array $headers Http Headers
     * @throws Exception
     */
    public function Response($view_filename,array $vars = array(),array $headers=array()){
        if(count($headers)){//add extra headers
            foreach($headers as $key=>$header){
                header($key.': '.$header);
            }
        }
        //pass to the view

        $view = new View($this->config->app_dir."/views/".$view_filename.".tpl",$vars,$this);
        $view->load();
        exit;
    }

    /**
     * Return a new Json Object Response
     * @param array $data Array to encode
     */
    public function JsonResponse(array $data = array()){
        header('Content-Type: application/json');//set headers
        echo json_encode($data);
        exit;
    }

    /**  Match 2 uris
     * @param $uri_segments
     * @param $route_segments
     * @return bool
     */
    private function CompareSegments($uri_segments,$route_segments,&$slugs){

        if(count($uri_segments) != count($route_segments)) return false;

        foreach($uri_segments as $segment_index=>$segment){

            $segment_route = $route_segments[$segment_index];
            //different segments must be an {slug}
            if(preg_match('/^{[^\/]*}$/',$segment_route))
                $slugs[] = $segment;//save slug key => value
            else if($segment_route != $segment && preg_match('/^{[^\/]*}$/',$segment_route) != 1) return false;

        }

        //match with every segment
        return true;
    }

    private  function getSegment($segment_number){
        $uri = isset($this->request->server['REQUEST_URI']) ? $this->request->server['REQUEST_URI'] : '/' ;
        $uri_segments = preg_split('/[\/]+/',$uri,null,PREG_SPLIT_NO_EMPTY);

        return isset($uri_segments[$segment_number]) ? $uri_segments[$segment_number] : false;
    }

    /**
     * Show framework's errors
     * @param string $msg
     * @param int $number
     * @return bool
     * @throws Exception
     */
    private function error($msg='',$number = 0){
        if($this->config->prod_env){
            return false;
        }
        else{
            $frw_msg =
                "<h1>FW Error</h1>
             <p>$msg</p><br/>";


            $frw_msg = $frw_msg." <h2>Trace:</h2>";
            echo $frw_msg;
            throw new Exception();
        }
    }
}

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