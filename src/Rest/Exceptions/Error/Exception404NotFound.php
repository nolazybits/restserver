<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:11 PM
 */

namespace Rest\Exceptions\Error;

/**
 * http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#4xx_Client_Error
 * The requested resource could not be found but may be available again in the future.
 * Subsequent requests by the client are permissible.
 *
 * Not found.
 * Either the request method and path supplied do not specify a known action in the API, or the object specified by the request does not exist.
 */
class Exception404NotFound
    extends \Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Rest\Http\StatusCodes::ERROR_NOT_FOUND;
        $this->message = $message;
    }
}