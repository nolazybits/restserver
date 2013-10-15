<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:02 PM
 */

namespace Rest\Exceptions\Redirection;
/**
 * http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#2xx_Success
 * Indicates that the resource has not been modified since the version specified by the request headers
 * If-Modified-Since or If-Match. This means that there is no need to retransmit the resource, since the client still
 * has a previously-downloaded copy.
 */

class Exception304NotModified
    extends \Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Rest\Http\StatusCodes::REDIRECT_NOT_MODIFIED;
        $this->message = $message;
    }
}
