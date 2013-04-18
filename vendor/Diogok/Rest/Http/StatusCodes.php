<?php
/**
 * User: zeflasher
 * Date: 20/03/13
 * Time: 4:50 PM
 */

namespace Diogok\Rest\Http;

class StatusCodes
{
    // http://en.wikipedia.org/wiki/HTTP_codes

    //  Standard response for successful HTTP requests.
    //  The actual response will depend on the request method used.
    //  In a GET request, the response will contain an entity corresponding to the requested resource.
    //  In a POST request the response will contain an entity describing or containing the result of the action.
    const SUCCESS_OK                = '200 OK';

    //  The request has been fulfilled and resulted in a new resource being created.
    const SUCCESS_CREATED           = '201 Created';

    //  The request has been fulfilled and the resource has been deleted (accepted deletion).
    const SUCCESS_ACCEPTED          = '202 Accepted';

    //  The server successfully processed the request, but is not returning any content.
    const SUCCESS_NO_CONTENT        = '204 No Content';

    //  The server successfully processed the request, but is not returning any content.
    const SUCCESS_REDIRECT            = '302 Found';

    //  The request cannot be fulfilled due to bad syntax.
    const ERROR_BAD_REQUEST         = '400 Bad Request';

    //  When authentication is possible but has failed or not yet been provided.
    const ERROR_UNAUTHORIZED        = '401 Unauthorized';

    // The request was a valid request, but the server is refusing to respond to it.
    const ERROR_FORBIDDEN           = '403 Forbidden';

    // The requested resource could not be found but may be available again in the future.
    const ERROR_NOT_FOUND           = '404 Not Found';

    //  Indicates that the resource requested is no longer available and will not be available again.
    const ERROR_GONE                = '410 Gone';

    const ERROR_SERVER              = '500 Internal Server Error';

}
