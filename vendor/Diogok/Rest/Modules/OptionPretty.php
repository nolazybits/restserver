<?php
namespace Diogok\Rest\Modules;

/**
 * Created by IntelliJ IDEA.
 * User: xavier
 * Date: 7/24/12
 * Time: 3:40 PM
 * To change this template use File | Settings | File Templates.
 */
class OptionPretty
    implements \Diogok\Rest\Controller
{
    const MODULE_OPT_PRETTY = "module_opt_pretty";

    /**
     * Will set the module_opt_pretty variable (array) as a parameter of the server
     * @param \Diogok\Rest\Server $server
     * @return \Diogok\Rest\Server
     */
    function execute(\Diogok\Rest\Server $server)
    {
        //  get the request URL parameters and find the opt_pretty array
        $pretty = $server->getRequest()->getGet("opt_pretty");
        if( isset($pretty) )
        {
            $server->setParameter(self::MODULE_OPT_PRETTY, true);
        }
        return $server;
    }

}
