<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:04 PM
 */

namespace Rest\Exceptions\Success;

/**
 * http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#2xx_Success
 * The request has been fulfilled and resulted in a new resource being created.
 *
 * Success (for object creation).
 * Its information is available in the data field at the top level of the response body.
 * The API URL where the object can be retrieved is also returned in the Location header of the response.
 */

class Exception201Created
    extends \Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Rest\Http\StatusCodes::SUCCESS_CREATED;
        $this->message = $message;
    }
}