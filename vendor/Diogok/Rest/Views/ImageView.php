<?php
namespace Csod\Rest;

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
        $response = @$rest->getParameter("response");
        $response = isset($response) ? $response : new ResponseData();

        // output the response header
        header(\Csod\Rest\Http\HeaderConstants::HTTP_VERSION_1_1.$response->code);

        if (!is_null($response->data))
        {
            header (\Csod\Rest\Http\HeaderConstants::CONTENT_TYPE_PNG);

            imagepng($response->data);
            imagedestroy($response->data);
        }

        return $rest;
    }

    /**/
}