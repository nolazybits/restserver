<?php
namespace Diogok\Rest\Modules;

/**
 * User: xavier
 * Date: 7/24/12
 * Time: 3:40 PM
 */
class OptionExtended
    implements \Diogok\Rest\Controller
{
    const MODULE_OPT_EXTENDED = "module_opt_extended";

    /**
     * Will set the module_opt_extended variable (array) as a parameter of the server
     * @param \Diogok\Rest\Server $server
     * @return \Diogok\Rest\Server
     */
    function execute(\Diogok\Rest\Server $server)
    {
        //  get the request URL parameters and find the opt_extended array
        $extended = $server->getRequest()->getGet("opt_extended");
        if( isset($extended) )
        {
            $properties = explode(",", $extended);
            $properties = array_combine($properties, $properties);
            $server->setParameter(self::MODULE_OPT_EXTENDED, $properties);
        }
        return $server;
    }
}
