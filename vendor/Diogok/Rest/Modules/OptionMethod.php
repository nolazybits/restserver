<?php
namespace Diogok\Rest\Modules;

/**
  * User: xavier
 * Date: 7/24/12
 * Time: 3:40 PM
 */
class OptionMethod
    implements \Diogok\Rest\Controller
{
    const MODULE_OPT_PRETTY = "module_opt_method";

    const HTTP_METHOD_GET = "GET";
    const HTTP_METHOD_POST = "POST";
    const HTTP_METHOD_PUT = "PUT";
    const HTTP_METHOD_DELETE = "DELETE";

    /**
     * Will set the module_opt_pretty variable (array) as a parameter of the server
     * @param \Diogok\Rest\Server $server
     * @return \Diogok\Rest\Server
     */
    function execute(\Diogok\Rest\Server $server)
    {
        $acceptedMethods = array(self::HTTP_METHOD_GET, self::HTTP_METHOD_POST, self::HTTP_METHOD_PUT, self::HTTP_METHOD_DELETE);

        //  get the request URL parameters and find the opt_method array
        $method = $server->getRequest()->getGet("opt_method");

        if( isset($method))
        {
            $method = strtoupper($method);
            if (in_array($method, $acceptedMethods) )
            {
                $server->getRequest()->setMethod($method);
                $_SERVER['REQUEST_METHOD'] = $method;
            }
        }
        return $server;
    }
}
