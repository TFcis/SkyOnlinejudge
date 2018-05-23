<?php namespace SkyOJ\API;

use \SkyOJ\API\HttpCode\HttpResponse;

class Ping extends ApiInterface
{
    use \SkyOJ\API\HttpCode\Http200;
    function apiCall(string $s): HttpResponse
    {
        return $this->http200("pong ! [{$s}]");
    }
}