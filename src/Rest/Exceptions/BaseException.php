<?php
namespace Rest\Exceptions;

/**
 *
 */
class BaseException 
  extends \Exception
{
    public $previousException;
}
