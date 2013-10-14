<?php
namespace Rest;
use Rest\Exceptions\Error\Exception404NotFound;
use Rest\Exceptions\Error\Exception500InternalServerError;

/**
* Class Server
* Is the front controller for mapping URL to controllers and dealing with Request/Response and Headers
* Made with Restful web services in mind.
*/
class Server
    extends \Rest\Extras
{

    private $response ;
    private $request ;
    private $authenticator ;

    private $baseUrl ; 
    private $query ;

    private $map ;
    private $matched ;
    private $params ;
    private $stack ;

    private $pre_map_modules;

    /**
     * Constructor
     * @param string $query Optional query to be treat as the URL
     * @return \Rest\Server $rest;
    */
    public function __construct($query=null)
    {
        parent::__construct();
        //  Request handler
        $this->request = new Request($this);
        //  Response holder
        $this->response = new Response($this);
        //  extensions the server handles
        $this->extensions = array();
        //  modules to be executed before mapping
        $this->pre_map_modules = array();

        //  set the base url
        if(isset($_SERVER["HTTP_HOST"]))
        {
            $this->baseUrl = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"]);
        }

        //  If will use custom URI or HTTP requested URI
        if($query===null) $this->query = $this->getRequest()->getRequestURI() ;
        else $this->query = $query ;

        $this->getRequest()->setURI($this->query);

        $this->matched = false;
    }

    /**
    * Sets a parameter in a global scope that can be recovered at any request.
    * @param mixed $key The identifier of the parameter
    * @param mixed $value The content of the parameter
    * @return \Rest\Server
    */
    public function setParameter($key,$value)
    {
        $this->params[$key] = $value ;
        return $this ;
    }

   /**
    * Return all parameters
    * @return mixed
    */
    public function getParameters() {
        return $this->params;
    }

    /**
    * Return the specified parameter
    * @param mixed $key The parameter identifier
    * @return mixed
    */
    public function getParameter($key)
    {
        return $this->params[$key];
    }


    /**
     * Set the URL to be handle or part of it
     * @param mixed $value The url
     * @return \Rest\Server
     */
    public function setQuery($value)
    {
        $this->getRequest()->setURI($value);
        return $this ;
    }

    /**
    * Get the URL or part of it, depreciated by RestRequest::getURI();
    * @param \string $k uri part
    * @return \string
    **/
    public function getQuery($k=null)
    {
        return $this->getRequest()->getURI($k);
    }  

    /**
    * Get the baseurl, based on website location (eg. localhost/website or website.com/);
    * @return string
    **/
    public function getBaseUrl()
    {
        return $this->baseUrl ;
    }

    /**
    * Get the Response handler object
    * @return \Rest\Response
    */
    public function getResponse()
    {
        return $this->response ;
    }

    /**
     * Get the Request handler object
    * @return \Rest\Request
    */
    public function getRequest()
    {
        return $this->request ;
    }

    /**
     * Get the Authentication handler object
    * @return \Rest\Authenticator
    */
    public function getAuthenticator()
    {
        return $this->authenticator ;
    }

    /**
     * Maps a Method and URL for a Class
     *
     * @param string $method The http method to be associated
     * @param string $uri The URL to be associated
     * @param string $class The name of the class to be called, it must implement RestAction
     * @param array $options An associative array specifying options for this resource<br />
     * Has the following format:<br />
     * [<br />
     *     'extensions' => ['json', 'xml'], <br />
     *     'pre_modules' => ['', ''],<br />
     *     'post_modules => ['', ''],<br />
     *     'cors' => true/false<br />
     * ]
     *
     * @return \Rest\Server
     */
    public function addMap( $method, $uri, $class, $options )
    {
        $map_resource = new MapResource($method, $uri, $class, $options);
        $this->map[$method][$uri] = $map_resource;
        return $this ;
    }

    /**
     * Get the class for specified method and uri
     * @param string $method The http method of the resource to retrieve
     * @param string $uri The uri of the resource to retrieve
     * @param string $extension
     * @return \Rest\MapResource
     * TODO Remove the server specific logic (checking extensions, ...) as this function must always return the MapResource if it is in the hashmap
     */
    public function getMap($method, $uri, $extension)
    {
        $maps = $this->map[$method];
        if(count($maps) < 1) { return false; }
        foreach($maps as $pattern=>$mapResource)
        {
            $parts = explode("/",$pattern) ;
            $map = array() ;
            foreach($parts as $part)
            {
                if(isset($part[0]) && $part[0] == ":" && $part[1] == "?")
                {
                    $map[] = "?[^/]*";
                }
                else if(isset($part[0]) && $part[0] == ":")
                {
                    $map[] = "[^/]+";
                }
                else
                {
                    $map[] = $part;
                }
            }
        //  TODO check for map specific extension
            if(preg_match("%^".implode("/", $map ).(!$extension?"":".".$extension)."$%",$uri) )
            {
                return $mapResource;
            }
        }
        return false ;
    }

    /**
     * Set matched pattern
     * @param array $map
     * @return \Rest\Server
     */
    public function setMatch($map)
    {
        $this->matched = $map;
        return $this;
    }

    /**
     * Get matched pattern
     * @return array
     */
    public function getMatch()
    {
        return $this->matched;
    }

    /**
    * Return last class name from RestServer stack trace
    * @return string 
    */
    public function lastClass()
    {
        $i = count($this->stack);
        return $this->stack[$i - 1];
    }

    /**
    * Run the Server to handle the request and prepare the response
    * @return string $responseContent
    */
    public function execute()
    {
        //  server modules executed
        $modules = $this->getPreModules();
        if( !empty($modules) )
        {
            $this->executeModules($modules);
        }

        //  check the server handle the requested extension
        $extension = $this->request->getExtension();
        if( $extension && !$this->hasExtension($extension) )
        {
            return false;
        }

        //  This is the class name to call
        $mapResource = $this->getMap($this->getRequest()->getMethod(),$this->getQuery(), $extension);
        $responseClass = null;
        if( $mapResource )
        {
            //  we have found a match for this uri
            $this->setMatch( explode("/", $mapResource->getUri()) );
            //  set the response class to be called
            $responseClass = $mapResource->getClass();
        }

        //  If no class was found, response is 404
        if(!$responseClass)
        {
            throw new \Rest\Exceptions\Error\Exception404NotFound();
/*            $this->getResponse()->cleanHeader();
            $this->getResponse()->addHeader("HTTP/1.1 404 Not found");
            $this->getResponse()->setResponse("HTTP/1.1 404 NOT FOUND");
            return $this->show();*/
        }

        //  resource modules executed
        $modules = $mapResource->getPreModules();
        if( !empty($modules) )
        {
            $this->executeModules($modules);
        }

        //  execute the main controller
        $this->executeController($responseClass);

        //  resource modules executed
        $modules = $mapResource->getPostModules();
        if( !empty($modules) )
        {
            $this->executeModules($modules);
        }

        //  server modules executed
        $modules = $this->getPostModules();
        if( !empty($modules) )
        {
            $this->executeModules($modules);
        }

        //  Call headers, if no yet done
        if( !$this->getResponse()->headerSent() )
        {
            $this->getResponse()->showHeader();
        }

        return $this->getResponse()->getResponse();
    }

    private function executeModules( $modules )
    {
        foreach($modules as $module)
        {
            $this->executeController($module);
        }
    }

    private function executeController( $response )
    {
        if( !is_string( $response ) && is_callable($response) )
        {
            $object = new \Rest\Controllers\Generic($response);
            $method = "execute";
        }
        else if( is_string($response) )
        {
            $response = explode("::",$response);
            if ( count($response) == 2)
            {
                $object =  new $response[0];
                $method = $response[1];
            }
            else
            {
                $object = new $response[0];
                $method = "execute";
            }
        }

        $this->call($object,$method);

        return $this;
    }

    /**
     * @param $object
     * @param null $method
     * @throws \Rest\Exceptions\Error\Exception500InternalServerError
     * @return \Rest\Server
     */
    private function call($object,$method)
    {
        $this->stack[] = get_class($object) ;
        if( !($object instanceof Action) )
        {
            Throw new \Rest\Exceptions\Error\Exception500InternalServerError(get_class($object)." is not a Rest\\Action");
        }
        else
        {
            $class = $object->$method($this);
        }

        if($class instanceof Action && get_class($class) != $this->lastClass())
        {
            return $this->call($class,"execute"); // May have another class to follow the request
        }
        return $this;
    }

    private function show()
    {
        if( !$this->getResponse()->headerSent() )
        {
            $this->getResponse()->showHeader(); // Call headers, if no yet
        }
        return $this->getResponse()->getResponse() ; // Return response content;
    }
}

?>
