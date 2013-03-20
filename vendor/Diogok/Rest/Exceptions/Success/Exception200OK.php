<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:02 PM
 */

namespace Diogok\Rest\Exceptions\Success;
/**
 * http://en.wikipedia.org/wiki/Http_status_codes#1xx_Informational
 * Standard response for successful HTTP requests.
 * The actual response will depend on the request method used.
 * In a GET request, the response will contain an entity corresponding to the requested resource.
 * In a POST request the response will contain an entity describing or containing the result of the action.
 *
 * Success.
 * If data was requested, it will be available in the data field at the top level of the response body.
 */

class Exception200OK
    extends \Diogok\Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Diogok\Rest\Http\StatusCodes::SUCCESS_OK;
        $this->message = $message;
    }
}
