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
     * @throws \Rest\Exceptions\Error\Exception412PreconditionFailed
     * @return \Rest\Server
     */
    function execute(\Rest\Server $server)
    {
        //  Get the method
        $method = $server->getRequest()->getMethod();

        //  calculate this resource etag
        $response = $server->getResponse();
        $etag = md5($response->getResponse());
        $response->addHeader('ETag: "'.$etag.'"');

        switch ($method)
        {
            //  if the resource we are asking for hasn't changed, for put, delete. this would be check in the controller
            case "HEAD":
            case "GET":
            $ifMatch = $server-> getRequest()-> getHeader("If-Match");
            //  the etag is the same, proceed
            if ($ifMatch === $etag)
            {
                throw new \Rest\Exceptions\Error\Exception412PreconditionFailed("Resource hasn't changed since last call");
            }
            break;
        }

    }
}