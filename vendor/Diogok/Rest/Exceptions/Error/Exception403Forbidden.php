<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 5:10 PM
 */

namespace Diogok\Rest\Exceptions\Error;

/**
 * http://en.wikipedia.org/wiki/Http_status_codes#1xx_Informational
 * The request was a valid request, but the server is refusing to respond to it.
 * Unlike a 401 Unauthorized response, authenticating will make no difference.
 * On servers where authentication is required, this commonly means that the provided credentials were successfully
 * authenticated but that the credentials still do not grant the client permission to access the resource
 * (e.g. a recognized user attempting to access restricted content).
 *
 * Access denied.
 * The API key was valid but the user does not have the access required to complete the request.
 * This can happen if you try to read or write to objects that the user does not have access to.
 */
class Exception403Forbidden
    extends \Diogok\Rest\Exceptions\RestException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct( $message = "", $code = 0, \Exception $previous = null )
    {
        $this->code = \Diogok\Rest\Http\StatusCodes::ERROR_FORBIDDEN;
        $this->message = $message;
    }
}
