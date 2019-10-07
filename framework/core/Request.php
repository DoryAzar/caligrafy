<?php

namespace Caligrafy;
use \Exception as Exception;

class Request  {
    
    /**
     * @var array _request has all the headers information of the request
     * @property array _request has all the headers information of the request
    */
    private $_request;

    /**
     * @var array uriParameters defines the uri request parameters 
     * @property array uriParameters defines the uri request parameters 
    */
    public $uriParameters;
    

    /**
     * @var array method fetches the http request method
     * @property array method fetches the http request method
    */
    public $method;
    
    /**
     * @var string uri defines the uri of the request without query parameters
     * @property string uri defines the uri of the request without query parameters
    */
    public $uri;
    
    /**
     * @var string fulluri defines the full uri of the request includng the query parameters
     * @property string fulluri defines the full uri of the request includng the query parameters
    */
    public $fullUri;
    
    /**
     * @var array uriComponents defines a list of all the elements of the uri
     * @property array uriComponents defines a list of all the elements of the uri
    */
    public $uriComponents;
    
    /**
     * @var string host reads the host information as taken from the headers of the request
     * @property string host reads the host information as taken from the headers of the request
    */
    public $host;
    
    /**
     * @var string host reads the host information as taken from the headers of the request
     * @property string host reads the host information as taken from the headers of the request
    */
    public $authorization;
    
    /**
     * @var array cache has all the headers information of the request
     * @property array cache has all the headers information of the request
    */
    public $cache;
    
    /**
     * @var array userAgent has all the headers information of the request
     * @property array userAgent has all the headers information of the request
    */
    public $userAgent;
    
    /**
     * @var array accept has all the headers information of the request
     * @property array accept has all the headers information of the request
    */
    public $accept;
    
    /**
     * @var array acceptEncoding has all the headers information of the request
     * @property array acceptEncoding has all the headers information of the request
    */
    public $acceptEncoding;
    
    /**
     * @var array acceptLanguage has all the headers information of the request
     * @property array acceptLanguage has all the headers information of the request
    */
    public $acceptLanguage;
    
    /**
     * @var array cookie has all the headers information of the request
     * @property array cookie has all the headers information of the request
    */
    public $cookie;
    
    /**
     * @var array parsedURI has all the headers information of the request
     * @property array parsedURI has all the headers information of the request
    */
    public $parsedURI;
    
    /**
     * @var array currentRoute has all the headers information of the request
     * @property array currentRoute has all the headers information of the request
    */
    public $currentRoute;
    
    /**
     * @var array currentBind has all the headers information of the request
     * @property array currentBind has all the headers information of the request
    */
    public $currentBind;
    
    /**
     * @var array fetch has all the headers information of the request
     * @property array fetch has all the headers information of the request
    */
    public $fetch;

    /**
     * @var array fetches all the request parameters
     * @property array fetches all the request parameters
    */
    public $parameters;

    
    public function __construct() 
    {
        $this->_request = getallheaders();
        $this->method = isset($_REQUEST['_method']) && $_REQUEST['_method'] != ''? strtoupper($_REQUEST['_method']) : $_SERVER['REQUEST_METHOD'];
        $this->host = $this->_request['Host']?? '';
        $this->authorization = $this->_request['Authorization']?? '';
        $this->cache = $this->_request['Cache']?? '';
        $this->userAgent = $this->_request['User-Agent']?? '';
        $this->accept = $this->_request['Accept']?? '';
        $this->acceptEncoding = $this->_request['Accept-Encoding']?? '';
        $this->acceptLanguage = $this->_request['Accept-Language']?? '';
        $this->cookie = $this->_request['Cookie']?? '';
        
        $this->uriParameters = $this->getURIParameters();
        $this->uri = isset($_REQUEST['uri']) && $_REQUEST['uri'] != ''? sanitizePath($_REQUEST['uri']) : '/';
        $this->fullUri = $this->fullUri();
        $this->uriComponents = $this->uri != '/'? explode('/', $this->uri) : array();
        $this->parsedURI = parsePath($this->uri);
        $this->currentRoute = currentRoute();
        $this->currentBind = currentBind()?? array();
        $this->parameters = empty($this->uriParameters)? $this->currentBind : $this->uriParameters;
        $this->fetch = (Object)($this->currentBind);
        return $this;
    }
    
    /**
     * Checks if API requests are authorized
     */
    public function authorizeApiRequest()
    {
        $result = false;
        $apiKey = isset($this->authorization)? $this->authorization : null;
        $matches = array();
        if ($apiKey) {
            preg_match('/Bearer\s(\S+)/', $apiKey, $matches);
            if(!empty($matches) && isset($matches[1])) {
                $result = encryptDecrypt('decrypt', $matches[1]) == getenv('APP_KEY')?? false;
            }
        }
        return $result;
    }
    
    /**
     * Checks if request needs a Json as a way to recognize API calls
     */
    public function wantsJson() 
    {
        $result = false;
        $acceptance = explode(',', strtolower(preg_replace('/\s+/', '', $this->accept)));
        if (!empty($acceptance)) {
            $result = !in_array('text/html', $acceptance); // set to true if client is not asking for html
        }
        return $result;
        
    }

    /**
     * Fetches all the arguments and parameters of a request call
     * 
     *@param $argument defines the argument looked up
     */
    public function fetch($argument) {
        // fetch by order of arguments
        if (is_int($argument)) {
            return array_values($this->currentBind)[$argument - 1]?? '';
        } else {
            // or by name
            return $this->fetch->$argument?? '';   
        }
    }



    /**
     * Fetches the full uri a request
     */
    public function fullUri() {
        $parametersQuery = http_build_query($this->uriParameters)?? '';
        return urldecode($this->uri."?".$parametersQuery);
    }
    
    /**
     * Fetches the parameters of a request
     */
    public function parameters()
    {
        $parameters = $this->getURIParameters();
        return $parameters;
        
    }
    
    /**
     * Fetches the files uploaded with a request
     */    
    public function files($fileAttribute)
    {
        return $this->getFiles($fileAttribute);
    }

    /**
     * Fetches all the parameters of a request depending on the method
     */
    private function getURIParameters() {
        $parameters = array();
        switch($this->method) {
            case 'GET':
                $parameters = $_GET?? array();
                break;
            case 'POST':
                $parameters = !empty($_POST)? $_POST : json_decode(file_get_contents("php://input"), true)?? array();
                $this->checkCSRF();
                break;
            case 'PUT':
                $parameters = !empty($_POST)? $_POST : json_decode(file_get_contents("php://input"), true)?? array();
                $this->checkCSRF();
                break;
            case 'DELETE':
                $parameters = !empty($_POST)? $_POST : json_decode(file_get_contents("php://input"), true)?? array();
                $this->checkCSRF();
                break;
            break;
        }
        return $parameters;
    }
    
    /**
     * Fetches all the files in a request
     */ 
    private function getFiles($fileAttribute)
    {
        return $_FILES[$fileAttribute]?? array();
    }
    
     /**
     * Checks for CSRF if it's an application form. If it's an api then it does not check it
     */    
    private function checkCSRF()
    {
        // if it's a form and not an API then check for CSRF
        if(!$this->wantsJson()) {
            if (!isset($_POST['_token']) || (isset($_POST['_token']) && !hash_equals($_POST['_token'], $_SESSION['token']))) {
                throw new Exception(Errors::get('3000'), 3000);
                exit;
            }         
        }
        return;
     
    }
    
}