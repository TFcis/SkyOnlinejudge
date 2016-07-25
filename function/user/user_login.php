<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function loginHandle()
{
    global $_E,$_G;
    if( $_G['uid'] ) {
        \Render::ShowMessage('你不是登入了!?');
        exit(0);
    }

    $email = \SKYOJ\safe_post('email');
    $AESenpass = \SKYOJ\safe_post('password');
    $GB = \SKYOJ\safe_post('GB');

    if( isset($email,$AESenpass,$GB) ) {
        if (!\userControl::CheckToken('LOGIN')) {
            \SKYOJ\throwjson('error', 'token error, please refresh page');
        }

        //recover password
        $exkey = unserialize($_SESSION['dhkey']);
        $key = md5($exkey->decode($GB));
        $iv = $_SESSION['iv'];

        $decode = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($AESenpass), MCRYPT_MODE_CBC, $iv);
        $password = rtrim($decode, "\0");

        $user = login($email, $password);
        if (!$user[0]) {
            $_E['template']['alert'] = $user[1];
            \LOG::msg(\Level::Notice, "<$email> want to login but fail.(".$user[1].')');
            \SKYOJ\throwjson('error', $user[1]);
        } else {
            $user = $user[1];
            \userControl::SetLoginToken($user['uid']);
            \SKYOJ\throwjson('SUCC', 'index.php');
        }
    }else{
        \userControl::RegisterToken('LOGIN', 600);
        $exkey = new \SKYOJ\DiffieHellman();
        $_SESSION['dhkey'] = serialize($exkey);
        $_SESSION['iv'] = \SKYOJ\GenerateRandomString(16, SET_HEX);
        $_E['template']['dh_ga'] = $exkey->getGA();
        $_E['template']['dh_prime'] = $exkey->getPrime();
        $_E['template']['dh_g'] = $exkey->getG();
        $_E['template']['iv'] = $_SESSION['iv'];

        \Render::setbodyclass('loginbody');
        \Render::render('user_login_box', 'user');
        exit(0);
    }
}
