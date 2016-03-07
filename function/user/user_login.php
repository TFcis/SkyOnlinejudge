<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
require_once($_E['ROOT'].'/function/common/encrypt.php');

if( $_G['uid'] )
{
    Render::ShowMessage("你不是登入了!?");
    exit('');
}

if( !isset($_POST['mod']) )
{
    userControl::RegisterToken('LOGIN',600);
    $exkey = new DiffieHellman();
    $_SESSION['dhkey'] = serialize($exkey);
    $_SESSION['iv'] = GenerateRandomString(16,SET_HEX);
    $_E['template']['dh_ga'] = $exkey->getGA();
    $_E['template']['dh_prime'] = DiffieHellman::PublicPrime;
    $_E['template']['dh_g'] = DiffieHellman::PublicG;
    $_E['template']['iv'] = $_SESSION['iv'];
    
    Render::setbodyclass('loginbody');
    Render::render('user_login_box','user');
    exit(0);
}
else // API CALL
{
    if( !userControl::CheckToken('LOGIN') )
        throwjson('error','token error, please refresh page');
    $email = safe_post('email');
    $AESenpass = safe_post('password');
    $GB = safe_post('GB');
    
    //recover password
    $exkey = unserialize($_SESSION['dhkey']);
    $key = md5($exkey->decode($GB));
    $iv  = $_SESSION['iv'];
    
    $decode = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,base64_decode($AESenpass),MCRYPT_MODE_CBC,$iv);
    $password = rtrim($decode,"\0");
    
    $user = login($email,$password);
    if( !$user[0]  )
    {
        $_E['template']['alert'] = $user[1];
        LOG::msg(Level::Notice,"<$email> want to login but fail.(".$user[1].")");
        throwjson('error',$user[1]);
    }
    else
    {
        $user = $user[1];
        userControl::SetLoginToken($user['uid']);
        throwjson('SUCC','index.php');
    }
}