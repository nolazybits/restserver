<?php
namespace Rest\Views;

/**
 *
 */
class ImageView
    implements \Rest\View
{
    /**
     * Render this view
     * @param \Rest\Server $rest
     * @return \Rest\Server
     */
    public function execute(\Rest\Server $rest)
    {
        // get the result value object to output
        $response_data = @$rest->getParameter("response");
        $response_data = isset($response_data) ? $response_data : new \Rest\Responses\ResponseData();

        $response = $rest->getResponse();
        $response->addHeader(\Rest\Http\HeaderConstants::HTTP_VERSION_1_1.$response_data->code);
        $response->addHeader(\Rest\Http\HeaderConstants::CONTENT_NO_CACHE);

        if (!is_null($response_data->data))
        {
            $response->addHeader(\Rest\Http\HeaderConstants::CONTENT_TYPE_PNG);
            $response->setResponse($response_data->data);
        }

        return $rest;
    }

    /**/
}