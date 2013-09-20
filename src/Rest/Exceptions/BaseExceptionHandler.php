<?php
namespace Rest\Exceptions;

/**
 *
 */
class BaseExceptionHandler
    implements \Rest\Exceptions\IExceptionHandler
{
    /**
     *
     */
    public function __construct()
    {
        set_exception_handler( array ( $this,'exceptionHandler' ) );
        set_error_handler( array( $this,'errorHandler' ) );
        register_shutdown_function(array ($this, 'shutDownHandler'));
    }

    /**
     * @param $exception
     */
    public function exceptionHandler ( $exception )
    {

    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public function errorHandler ( $errno, $errstr, $errfile, $errline )
    {

    }

    /**
     *
     */
    public function shutDownHandler()
    {

    }

    /**/
}
