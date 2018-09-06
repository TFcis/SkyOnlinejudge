<?php namespace SkyOJ\API\HttpCode;

class HttpResponse extends \Exception
{
    protected $code;
    protected $data;

    function __construct(int $code, $data)
    {
        $this->code = $code;
        $this->data = $data;
    }

    function code()
    {
        return $this->code;
    }

    function data()
    {
        return $this->data;
    }
}