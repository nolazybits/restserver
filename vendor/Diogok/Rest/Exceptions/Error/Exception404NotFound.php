<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:11 PM
 */

namespace Diogok\Rest\Exceptions\Error;

/**
 * http://en.wikipedia.org/wiki/Http_status_codes#1xx_Informational
 * The requested resource could not be found but may be available again in the future.
 * Subsequent requests by the client are permissible.
 *
 * Not found.
 * Either the request method and path supplied do not specify a known action in the API, or the object specified by the request does not exist.
 */
class Exception404NotFound
    extends \Diogok\Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Diogok\Rest\Http\StatusCodes::ERROR_NOT_FOUND;
        $this->message = $message;
    }
}