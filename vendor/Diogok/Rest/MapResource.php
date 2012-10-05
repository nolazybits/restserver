<?php
/**
 * Created by IntelliJ IDEA.
 * User: xavier
 * Date: 7/18/12
 * Time: 3:55 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Diogok\Rest;
class MapResource
    extends \Diogok\Rest\Extras
{

    /**
     * @var \string
     */
    private $method;

    /**
     * @var \string
     */
    private $uri;

    /**
     * The controller or method this map points to
     * @var String;
     */
    private $class;

    /**
     * @param \string $method       http method
     * @param \string $uri          Resource URI
     * @param \string $class        class and or function qualifying string
     * @param \array $extensions    supported extension for this resource. used to override server defaults
     * @param \array $pre_modules   modules to be executed before the mapped one
     * @param \array $post_modules  modules to be executed after the mapped one
     */
    public function __construct($method, $uri, $class, $extensions = null, $pre_modules = null, $post_modules = null)
    {
        $this->method       = $method;
        $this->uri          = $uri;
        $this->class        = $class;
        $this->addExtensions($extensions);
        $this->addPreModules($pre_modules);
//        $this->post_modules  = $post_modules;
    }

    /**
     * @param String $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return String
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
