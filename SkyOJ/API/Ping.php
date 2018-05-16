<?php namespace SkyOJ\API;

class Ping extends ApiInterface
{
    function apiCall()
    {
        return "pong ";
    }
}