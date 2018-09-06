<?php namespace SkyOJ\API;

use \SkyOJ\API\HttpCode\HttpResponse;

abstract class ApiInterface
{
    protected $m_skyoj;
    function __construct($skyoj)
    {
        $this->m_skyoj =& $skyoj;
    }

    function run(...$var): HttpResponse
    {
        return $this->apiCall(...$var);
    }
}
