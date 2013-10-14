<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:12 PM
 */

namespace Rest\Exceptions\Error;

/**
 * http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#5xx_Server_Error
 * The request cannot be fulfilled due to bad syntax.
 *
 * Invalid request.
 * his usually occurs because of a missing or malformed parameter. Check the documentation and the syntax of your request and try again.
 */
class Exception500InternalServerError
    extends \Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Rest\Http\StatusCodes::ERROR_SERVER;
        $this->message = $message;
    }
}