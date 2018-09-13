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
    $password = \SKYOJ\safe_post('password');
	$user_ip = \SKYOJ\get_ip();

    if( isset($email,$password) ) {
        if (!\userControl::CheckToken('LOGIN')) {
            \SKYOJ\throwjson('error', 'token error, please refresh page');
        }

        $user = login($email, $password, $user_ip);
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
        $_SESSION['iv'] = \SKYOJ\GenerateRandomString(16, SET_HEX);
        $_E['template']['iv'] = $_SESSION['iv'];

        \Render::setbodyclass('loginbody');
        \Render::render('user_login_box', 'user');
        exit(0);
    }
}
