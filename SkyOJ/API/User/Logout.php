<?php namespace SkyOJ\API\User;

use \SkyOJ\API\HttpCode\HttpResponse;
use \SkyOJ\API\ApiInterface;

class Logout extends ApiInterface
{
    use \SkyOJ\API\HttpCode\Http200;

    function apiCall(): HttpResponse
    {
        //TODO: Rewrite me
        \userControl::DelLoginToken();
        \userControl::RemoveCookie('uid');
        return $this->http200();
    }
}
