<?php
namespace Diogok\Rest;

/**
 * User: xavier
 * Date: 7/24/12
 * Time: 3:33 PM
 */
interface Modules
{
    /**
     * @param \Diogok\Rest\Server $server
     * @return \Diogok\Rest\Server
     */
    function execute(\Diogok\Rest\Server $server);
}
