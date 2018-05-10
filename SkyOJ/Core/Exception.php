<?php namespace SkyOJ\Core;

class Exception extends \Exception
{
    public function __construct(string $msg,int $code = ErrorCode::UNKNOWN_ERROR , Exception $previous = null)
    {
        parent::__construct(ErrorCode::str($code).':'.$msg, $code, $previous);
    }
}