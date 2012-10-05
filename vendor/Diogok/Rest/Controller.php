<?php

/** Class RestController
 * Describe a possible Controller to handle a Request
 * Namespace update: zeflasher
 */
namespace Diogok\Rest;
interface Controller extends Action {
     /**
     * Execute the Default action of this controller
     * @param \Diogok\Rest\Server $restServer
     * @return \Diogok\Rest\Action|\Diogok\Rest\View|\Diogok\Rest\Controller
     */
    function execute(\Diogok\Rest\Server $restServer) ;
}

?>
