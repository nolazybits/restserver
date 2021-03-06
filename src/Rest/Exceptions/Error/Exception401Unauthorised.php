<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:07 PM
 */

namespace Rest\Exceptions\Error;

/**
 * http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#4xx_Client_Error
 * Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided.
 * The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource.
 *
 * No authorization.
 * A valid API key was not provided with the request, so the API could not associate a user with the request.
 */
class Exception401Unauthorised
    extends \Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Rest\Http\StatusCodes::ERROR_UNAUTHORIZED;
        $this->message = !empty($message)?$message:"You are not authorized to access this resource";
    }
}