<?php namespace SkyOJ\API\User;

use \SkyOJ\API\ApiInterface;
use \SkyOJ\API\ApiInterfaceException;

class Logout extends ApiInterface
{
    function apiCall()
    {
        //TODO: Rewrite me
        \userControl::DelLoginToken();
        \userControl::RemoveCookie('uid');
        return true;
    }
}
