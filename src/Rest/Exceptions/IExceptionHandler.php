<?php
namespace Rest\Exceptions;
/**
 *
 */
interface IExceptionHandler
{
    function exceptionHandler( $exception );
    function errorHandler($errno, $errstr, $errfile, $errline);
    function shutDownHandler ();
}
