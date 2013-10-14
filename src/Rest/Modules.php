<?php
namespace Rest\Modules;

/**
 * User: xavier
 * Date: 7/24/12
 * Time: 3:33 PM
 */
interface Modules
    extends \Rest\Controller
{
    /**
     * @param \Rest\Server $server
     * @return \Rest\Server
     */
    function execute(\Rest\Server $server);
}
