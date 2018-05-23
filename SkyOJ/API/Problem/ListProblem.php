<?php namespace SkyOJ\API\Problem;

use \SkyOJ\API\HttpCode\HttpResponse;
use \SkyOJ\API\ApiInterface;

class ListProblem extends ApiInterface
{
    use \SkyOJ\API\HttpCode\Http200;
    function apiCall(int $offest, int $number): HttpResponse
    {
        return $this->http200([]);
    }
}