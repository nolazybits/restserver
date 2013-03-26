<?php
namespace Diogok\Rest\Views;

/**
 *
 */
class ImageView
    implements \Diogok\Rest\View
{
    /**
     * Render this view
     * @param \Diogok\Rest\Server $rest
     * @return \Diogok\Rest\Server
     */
    public function show(\Diogok\Rest\Server $rest)
    {
        // get the result value object to output
        $response_data = @$rest->getParameter("response");
        $response_data = isset($response_data) ? $response_data : new \Diogok\Rest\Responses\ResponseData();

        $response = $rest->getResponse();
        $response->addHeader(\Diogok\Rest\Http\HeaderConstants::HTTP_VERSION_1_1.$response->code);

        if (!is_null($response_data->data))
        {
            $response->addHeader(\Diogok\Rest\Http\HeaderConstants::CONTENT_TYPE_PNG);
            $response->setResponse($response_data->data);
        }

        return $rest;
    }

    /**/
}