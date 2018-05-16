<?php namespace SkyOJ\API\User;

use \SkyOJ\API\ApiInterface;
use \SkyOJ\API\ApiInterfaceException;

use SkyOJ\Core\User\User;

class Register extends ApiInterface
{
    function apiCall(string $username, string $password, string $email)
    {
        if( !User::creatable($this->m_skyoj->getCurrentUser()) )
            return false;
        
        try
        {
            $new_uid = User::create($username, $password, $email);
        }
        catch(\SkyOJ\Core\CommonObjectError $e)
        {
            throw new ApiInterfaceException(-1, "Username or email has been used");
        }
            
        return $new_uid;
    }
}