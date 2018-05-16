<?php namespace SkyOJ\API;

abstract class ApiInterface
{
    protected $m_skyoj;
    function __construct($skyoj)
    {
        $this->m_skyoj =& $skyoj;
    }

    function run(...$var)
    {
        $this->apiCall(...$var);
    }
}