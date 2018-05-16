<?php namespace SkyOJ\API;

class ApiInterfaceException extends \Exception
{
    public function __construct(int $code, string $msg, Exception $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }
}