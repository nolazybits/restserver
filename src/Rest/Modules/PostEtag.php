<?php
/**
 * Created by IntelliJ IDEA.
 * User: nolazybits
 * Date: 14/10/13
 * Time: 8:24 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Rest\Modules;


class PostEtag
    implements \Rest\Modules
{

    /**
     * @param \Rest\Server $server
     * @return \Rest\Server
     */
    function execute(\Rest\Server $server)
    {
        $response = $server->getResponse();
        $etag = md5($response->getResponse());
        $response->addHeader('ETag: W/"'.$etag.'"');
    }
}