<?php namespace SkyOJ\API\User;

use SkyOJ\API\HttpCode\HttpResponse;
use \SkyOJ\API\ApiInterface;
use \SkyOJ\API\ApiInterfaceException;

use SkyOJ\Core\User\User;

class Register extends ApiInterface
{
    use \SkyOJ\API\HttpCode\Http200;
    use \SkyOJ\API\HttpCode\Http403;
    use \SkyOJ\API\HttpCode\Http409;

    function apiCall(string $username, string $password, string $email): HttpResponse
    {
        //TODO : Imp me!
        if( !User::creatable($this->m_skyoj->getCurrentUser()) )
            return $this->http403(['username', 'email']);

        try
        {
            $passhash = password_hash($password, PASSWORD_BCRYPT);
            $new_uid = User::create($username, $passhash, $email);
            if( !$new_uid )
                return $this->http409();
            return $this->http200($new_uid);
        }
        catch(\SkyOJ\Core\CommonObjectError $e)
        {
            return $this->http409();
            //throw new ApiInterfaceException(-1, "Username or email has been used");
        }
    }
}