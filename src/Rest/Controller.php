<?php
namespace Rest;

/** 
 * interface Rest\Controller
 * Describe a possible Controller to handle a Request
 */
interface Controller
    extends Action
{
     /**
     * Execute the Default action of this controller
     * @param \Rest\Server $restServer
     * @return \Rest\Action|\Rest\View|\Rest\Controller
     */
    function execute(\Rest\Server $restServer) ;
}
