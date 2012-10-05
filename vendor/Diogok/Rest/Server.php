<?php
/**
* Class Server
* Is the front controller for mapping URL to controllers and dealing with Request/Response and Headers
* Made with Restful web services in mind.
* By Diogo Souza da Silva <manifesto@manifesto.blog.br>
*/
namespace Diogok\Rest;
class Server
    extends \Diogok\Rest\Extras
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
     * @return \Diogok\Rest\Server $rest;
    */
    public function __construct($query=null)
    {
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
    * @return \Diogok\Rest\Server
    */
    public function setParameter($key,$value)
    {
        $this->params[$key] = $value ;
        return $this ;
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
     * @return \Diogok\Rest\Server
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
    * @return \Diogok\Rest\Response
    */
    public function getResponse()
    {
        return $this->response ;
    }

    /**
     * Get the Request handler object
    * @return \Diogok\Rest\Request
    */
    public function getRequest()
    {
        return $this->request ;
    }

    /**
     * Get the Authentication handler object
    * @return \Diogok\Rest\Authenticator
    */
    public function getAuthenticator()
    {
        return $this->authenticator ;
    }

    /**
     * Maps a Method and URL for a Class
     * @param \Diogok\Rest\MapResource $map_resource defines a uri mapping
     * @return \Diogok\Rest\Server
     */
    public function addMap( $map_resource )
    {
        $this->map[$map_resource->getMethod()][$map_resource->getUri()] = $map_resource;
        return $this ;
    }

    /**
    * Get the class for specified method and uri
    * @param string $method
    * @param string $uri
    * @return \Diogok\Rest\MapResource
    */
    public function getMap($method,$uri)
    {
        //  check the server handle the requested extension
        $extension = $this->request->getExtension();
        if( $extension && !$this->hasExtension($extension) )
        {
            return false;
        }

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
                $this->setMatch($parts);
                return $mapResource;
            }
        }
        return false ;
    }

    /**
     * Set matched pattern
     * @param array $map
     * @return \Diogok\Rest\Server
     */
    public function setMatch($map) {
        $this->matched = $map;
        return $this;
    }

    /**
     * Get matched pattern
     * @return array
     */
    public function getMatch() {
        return $this->matched;
    }

    /**
    * Return last class name from RestServer stack trace
    * @return string 
    */
    public function lastClass() {
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
        $this->executeModules($modules);

        //  This is the class name to call
        $mapResource = $this->getMap($this->getRequest()->getMethod(),$this->getQuery()) ;
        $responseClass = null;
        if( $mapResource )
        {
            $responseClass = $mapResource->getClass();
        }

        //  If no class was found, response is 404
        if(!$responseClass)
        {
            $this->getResponse()->cleanHeader();
            $this->getResponse()->addHeader("HTTP/1.1 404 Not found");
            $this->getResponse()->setResponse("HTTP/1.1 404 NOT FOUND");
            return $this->show();
        }

        //  resource modules executed
        $modules = $mapResource->getPreModules();
        if( !empty($modules) )
        {
            $this->executeModules($modules);
        }

        //  execute the main controller
        return $this->executeController($responseClass);
    }

    private function executeModules( $modules )
    {
        foreach($modules as $module)
        {
            $moduleMethod = null;
            // In case a specific method should be called
            if(is_string($module) && count($parts = explode("::",$module)) > 1)
            {
                $module = $parts[0];
                $moduleMethod = $parts[1];
            }

            if(is_callable($module))
            {
                $moduleObject = $module;
            }
            else
            {
                $moduleObject = new $module;
            }

            $this->call($moduleObject,$moduleMethod)->show();
        }
    }

    private function executeController( $class )
    {
        $controllerMethod = null;
        // In case a specific method should be called
        if(is_string($class) && count($parts = explode("::",$class)) > 1)
        {
            $class = $parts[0];
            $controllerMethod = $parts[1];
        }

        if(is_callable($class))
        {
            $controllerObject = $class;
        }
        else
        {
            $controllerObject = new $class;
        }

        return $this->call($controllerObject,$controllerMethod)->show();
    }

    /**
     * @param \Diogok\Rest\Controller|\Diogok\Rest\View|\string $class
     * @param null $method
     * @return Server
     * @throws \Exception
     */
    private function call($class,$method=null) {             
        $this->stack[] = get_class($class) ;

        if(is_callable($class))
        {
            $class = $class($this);
        }
        else if($method != null)
        {
            $class = $class->$method($this);
        }
        //  If is a view, call Show($restServer)
        else if($class instanceof \Diogok\Rest\View)
        {
            $class = $class->show($this);
        }
        //  If is a controller, call execute($restServer)
        else if($class instanceof \Diogok\Rest\Controller)
        {
            $class = $class->execute($this);
        }
        else
        {
            Throw new \Exception(get_class($class)." is not a RestAction");
        }

        if($class instanceof Action
            && get_class($class) != $this->lastClass() ) {
            return $this->call($class); // May have another class to follow the request
        }

        return $this ;
    }

    private function show() {
        if(!$this->getResponse()->headerSent()) {
            $this->getResponse()->showHeader(); // Call headers, if no yet
        }
        return $this->getResponse()->getResponse() ; // Return response content;
    }
}

?>
