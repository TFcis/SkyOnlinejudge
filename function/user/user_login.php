<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

if( !isset($_POST['mod']) )
{
    $Render->render('user_login_box','user');
    exit('');
}
else
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user; 
    if( !($user = login($email,$password)) )
    {
        $Render->render('user_login_box','user');
        exit('');
    }
    else
    {
        $logintoken = md5(uniqid('login_',true));
        $_SESSION['logintoken'][$logintoken] = $user;
        setcookie('token',$logintoken,time()+3600);
        header("Location:index.php");
    }
}