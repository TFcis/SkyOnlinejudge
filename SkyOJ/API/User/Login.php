<?php namespace SkyOJ\API\User;

use \SkyOJ\API\ApiInterface;
use \SkyOJ\API\ApiInterfaceException;

class Login extends ApiInterface
{
    function apiCall(string $username, string $password)
    {
        //TODO: rewrite me
        $user = login($username, $password);
        if (!$user[0]) {
            \LOG::msg(\Level::Notice, "<$username> want to login but fail.(".$user[1].')');
            throw new ApiInterfaceException( -1 , "login fail" );
        } else {
            \userControl::SetLoginToken($user[0]);
            return true;
        }
    }
}

function CheckEmailFormat(string $email)
{
    $pattern = '/^[A-z0-9_.]{1,30}@[A-z0-9_.]{1,20}$/';

    return preg_match($pattern, $email);
}

function login(string $userinput, string $password)
{
    $acctable = \DB::tname('account');
    $sqlres;
    $userdata = null;
    $resultdata = [false, ''];

    $email = $userinput;
    if (!CheckEmailFormat($email)) {
        $res = \DB::fetchEx("SELECT `email` FROM `$acctable` WHERE `nickname`=?", $userinput);
        if ($res === false) {
            $resultdata[1] = '暱稱錯誤';

            return $resultdata;
        }
        $email = $res['email'];
    }

    $userdata = \DB::fetch("SELECT * FROM  `$acctable`".
                        'WHERE  `email` = ?', [$email]);
    if ($userdata === false) {
        $resultdata[1] = '無此帳號';

        return $resultdata;
    }
    if (!password_verify($password, $userdata['passhash'])) {
        $resultdata[1] = '密碼錯誤';

        return $resultdata;
    }
    $resultdata[0] = true;
    $resultdata[1] = $userdata;

    return $resultdata;
}