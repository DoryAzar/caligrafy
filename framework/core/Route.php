<?php
/**
 * Route.php
 * Class that builds the routing logic of the framework
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class that builds the routing logic of the framework
 * @version 1.0
 *
*/

class Route
{
    /**
     * @var array $_httpMethods defines all the valid http methods that are set at runtime
     * @property array $_httpMethods defines all the valid http methods at runtime
    */
    private static $_httpMethods = array();
    
    /**
     * @var array $_routes defines all the valid routes that are set at runtime
     * @property array $_routes defines all the valid routes at runtime
    */
    private static $_routes = array();

    /**
     * @var array $_routesComponents defines all the valid routes components that are set at runtime
     * @property array $_routesComponents defines all the valid routes components that are set at runtime
    */
    private static $_routesComponents = array();

    /**
     * @var array $_methods defines all the methods that are set at runtime
     * @property array $_methods defines all the methods that are set at runtime
    */
    private static $_methods = array();
    
    /**
     * @var int $_currentRouteKey defines the currently run route key
     * @property int $_currentRouteKey defines the currently run route key
    */
    private static $_currentRouteKey;
    

    /**
     * @var int $_currentRouteComponents defines the currently run route components 
     * @property int $_currentRouteComponents defines the currently run route components 
    */
    private static $_currentRouteComponents = array();

    
    /**
     * @var Request $_request defines the request
     * @property Request $_request defines the request
    */
    private static $_request;


    /**
     * @var string $currentRoute defines the currently run route
     * @property string $currentRoute defines the currently run route
    */
    public static $currentRoute;

    /**
     * @var string $currentBind defines variables bound to the route
     * @property string $currentBind defines variables bound to the route
    */
    public static $currentBind;


    /**
     * Runs the routes based upon a request
    */
    public static function run()
    {
        //fetch the request
        self::$_request = request();

        // Evaluate the Route to get the full expression with the variables bounded
        self::evaluate();

        // Bind the route variables to the request uri
        self::bind();

        // execute the routing
        self::execute();

    }
    

    /**
     * Evaluates the route and binds it with variables from the request
    */
    public static function evaluate() {
        $requestMethod = self::$_request->method;
        $uriComponents = self::$_request->uriComponents;

        $route = self::$_routesComponents;

        // clean up all the components that have a different size and a different method
        foreach(self::$_routesComponents as $key => $value) {
             if (sizeof($value) == 0 || sizeof($value) != 0 && (sizeof($value) != sizeof($uriComponents) || self::$_httpMethods[$key] != $requestMethod))
             {
                unset($route[$key]);
             } 
        }

        // among the remaining components keep only the values that match the pattern
        foreach($route as $k => $v) {
            //go through all the defined routes
            $path = implode('/', $v);
            foreach($v as $key => $value) {
                // replace all the variable patterns with values from uri
                if(preg_match(ROUTE_ARG_PATTERN, $value)) {
                     $route[$k][$key] =  $uriComponents[$key];
                }
            }
            //if evaluated route is not the same as uri, unset
            $currentPath = urldecode(implode('/', $route[$k]));
            if ($currentPath != self::$_request->uri) {
                unset($route[$k]);
            }            
        } 
        self::$_currentRouteKey = $route? array_keys($route)[0]?? 0 : 0;
        self::$_currentRouteComponents = self::$_routesComponents[self::$_currentRouteKey];
        self::$currentRoute = urldecode(implode('/', self::$_routesComponents[self::$_currentRouteKey]));
    }

    /**
     * Binding the route variables
    */

    public static function bind() {

        $uriPathComponent = implode('/', self::$_currentRouteComponents);

        // Bind route to variables
        self::$currentBind = bindRouteToVar($uriPathComponent, self::$_request->uri);
        
        // if there are any additional parameters from GET
        if (self::$_httpMethods[self::$_currentRouteKey] == 'GET') {
            self::$currentBind = array_merge(self::$currentBind, self::$_request->parameters);
        }
    }

    /**
     * Executing the routing
    */
    public static function execute() {

         // If no method set, a route to view is implied
        if (self::$_methods[self::$_currentRouteKey] == '') {
            echo "A method needs to be specified";
            
        } else if (is_string(self::$_methods[self::$_currentRouteKey])) {
            // if the method is a string then parse Controller and Action
            $parsedMethod = explode('@',self::$_methods[self::$_currentRouteKey]);
            $controller = $parsedMethod[0];
            $action = $parsedMethod[1]?? 'index';
        
            
            // Generate Exception if Controller or the Action do not exist 
            if (!method_exists($controller, $action)) {
                throw new Exception(Errors::get(1001), 1001);
                exit;
            }

            // Generate Exception if it is an API access and it is not allowed
            if (self::$_request->wantsJson() && !Auth::api()) {
                throw new Exception(Errors::get(1004), 1004);
                exit;
            }
        
            // Execute if Controller and Action exist
            $controller = new $controller('Model');
            $controller->$action();

        } else {
            // if method is not a string then assumption is that it is a callback function
            call_user_func(self::$_methods[self::$_currentRouteKey]);
        }

    }
    
    
    /**
     * GET http method handler
     * @param string $route defines the uri
     * @param object $method defines the method to be used, it could be a String or a callback function
    */
    public static function get($route, $method = null)
    {
        self::$_httpMethods[] = 'GET';
        self::set($route, $method);
    }
    
    /**
     * POST http method handler
     * @param string $route defines the uri
     * @param object $method defines the method to be used, it could be a String or a callback function
    */
    public static function post($route, $method = null)
    {
        self::$_httpMethods[] = 'POST';
        self::set($route, $method);
    }
    
    /**
     * PUT http method handler
     * @param string $route defines the uri
     * @param object $method defines the method to be used, it could be a String or a callback function
    */
    public static function put($route, $method = null)
    {
        self::$_httpMethods[] = 'PUT';
        self::set($route, $method);
    }
    
    /**
     * DELETE http method handler
     * @param string $route defines the uri
     * @param object $method defines the method to be used, it could be a String or a callback function
    */
    public static function delete($route, $method = null)
    {
        self::$_httpMethods[] = 'DELETE';
        self::set($route, $method);
    }
    
    /**
     * Private mthod that sets the routes at runtime
     * @param string $route defines the uri
     * @param object $method defines the method to be used, it could be a String or a callback function
    */
    private static function set($route, $method = null)
    {
        $route = sanitizePath($route);
        $routeWithNoParameters = explode('?', $route)?? array();
        $route = $routeWithNoParameters? $routeWithNoParameters[0] : $route;
        self::$_routes[] = $route;
        self::$_routesComponents[] = parsePath($route);
        self::$_methods[] = $method?? '';
    }

}