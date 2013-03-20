<?php
namespace Diogok\Rest\Exceptions;
/**
 *
 */
class ExceptionHandler extends BaseExceptionHandler
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $exception
     */
    public function exceptionHandler ( $exception )
    {
        $output['errors'] = array();
        $error = new \stdClass();
        $error->message = $exception->getMessage();
        array_push($output['errors'], $error );

        if (REST_DEBUGGING === true)
        {
            $debugging = ob_get_contents();
            if (!empty($debugging))
            {
                $errors = explode("\n", $debugging);
                if (count($errors) > 1)
                {
                    for ($i = 0; $i < count($errors); $i++)
                    {
                        $error = new \stdClass();
                        $error->debug = $errors[$i];
                        if (!empty($error->debug))
                        {
                            array_push($output['errors'], $error );
                        }
                    }
                }
                else
                {
                    $error = new \stdClass();
                    $error->message = $debugging;
                    array_push($output['errors'], $error );
                }
            }
        }

        ob_clean();
        ob_start();

        header(\Diogok\Rest\Http\HeaderConstants::HTTP_VERSION_1_1 . $exception->getCode() );
        header(\Diogok\Rest\Http\HeaderConstants::CONTENT_TYPE_JSON);

        $output = json_encode($output);

        echo(  $output );
        ob_flush();

    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public function errorHandler ( $errno, $errstr, $errfile, $errline )
    {
        //echo "Caught error $errstr<br />";
        //throw new \Csod\Exceptions\Rest\Error\Code400BadRequestException("Found error");
    }

    /**
     *
     */
    public function shutDownHandler()
    {
        $lasterror = error_get_last();

        switch ($lasterror['type'])
        {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_PARSE:
                $error = "[SHUTDOWN] lvl:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | ln:" . $lasterror['line'];

        }
    }

    /**/
}
