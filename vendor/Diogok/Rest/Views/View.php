<?php
namespace Csod\Rest;

class View
    implements \Diogok\Rest\View
{
    /**
     * @var stdClass
     */
    protected $body;

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
            $this->body = new \stdClass();
            $this->body->data = $response->data;

            // generate the content type header and body
            $extension = strtolower($rest->getRequest()->getExtension());
            switch ($extension)
            {
                case "html";
                    $rest = $this->html($rest);
                    break;

                default:
                    $rest = $this->json($rest);
            }
        }

        return $rest;
    }

    /**
     * Html
     * @param \Diogok\Rest\Server $rest
     * @return \Diogok\Rest\Server
     */
    public function html(\Diogok\Rest\Server $rest)
    {
        $response = $rest->getResponse();
        $response->addHeader(\Csod\Rest\Http\HeaderConstants::CONTENT_TYPE_HTML.\Csod\Rest\Http\HeaderConstants::CHARSET_UTF8);

        if (is_object($this->body->data))
        {
            $formattedResponse = "<pre>".print_r($this->body->data."</pre>", true);
        }
        else
        {
            $formattedResponse = "<p>".$this->body->data."</p>";
        }

        $response->setResponse($formattedResponse);
        return $rest;
    }


    /**
     * @param \Diogok\Rest\Server $rest
     * @return \Diogok\Rest\Server
     */
    public function json(\Diogok\Rest\Server $server)
    {
        $response = $server->getResponse();
        $response->addHeader(\Csod\Rest\Http\HeaderConstants::CONTENT_TYPE_JSON.\Csod\Rest\Http\HeaderConstants::CHARSET_UTF8);

        $encoded_response = json_encode($this->body);

        if ($encoded_response === false)
        {
            // TODO - throw an exception
        }

        $opt_pretty = $server->getParameter(\Diogok\Rest\Modules\OptionPretty::MODULE_OPT_PRETTY);
        if ( isset($opt_pretty) )
        {
            $encoded_response = $this->indent($encoded_response);
        }
        $response->setResponse($encoded_response);
        return $server;
    }

    /**
     * @param \Diogok\Rest\Server $rest
     * @return ResponseData|mixed
     */
    private function getResult(\Diogok\Rest\Server $rest)
    {
        $response = $rest->getParameter("response");
        return isset($response) ? $response : new ResponseData();
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     * @param $json  The original JSON string to process.
     * @return string  Indented version of the original JSON string.
     */
    private function indent($json)
    {
        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '  ';
        $newLine     = "\n";
        $prevChar    = '';
        $outOfQuotes = true;

        for ($i=0; $i<=$strLen; $i++)
        {
            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\')
            {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $outOfQuotes)
            {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++)
                {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes)
            {
                $result .= $newLine;
                if ($char == '{' || $char == '[')
                {
                    $pos ++;
                }

                for ($j = 0; $j < $pos; $j++)
                {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }

    /**/
}