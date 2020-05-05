<?php
namespace FrameWork;
use Exception;
use PDO;
use stdClass;

class Kernel
{
    /** @var FWConfig **/
    protected $config;
    /** @var Request */
    protected $request;
    /** @var PDO */
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
                    Session::close();
                    call_user_func_array($func, $slugs);

               break;
            }

        if(!$run) $this->error('Not route found',1);
    }

    /**
     * @return PDO
     * @throws Exception
     */
    public function getDB(){
        return $this->db?$this->db:$this->buildPDO();
    }

    /**
     * @return PDO
     */
    private function buildPDO()
    {
        if($this->db)
        {
            return $this->db;
        }
        $dsn = "mysql:host={$this->config->db_host};dbname={$this->config->db_name};";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $this->config->db_user, $this->config->db_password, $opt);
    }
    private function buildRequest(){
        $this->request = new Request();
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

    /**
     * Check if it's post request
     * @return bool
     */
    public function IsPost()
    {
        if (!empty($_POST))
        {
            return true;
        }
        return false;
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
