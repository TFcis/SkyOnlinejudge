<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
unset($_SESSION['logintoken'][$_COOKIE['token']]);
setcookie('token', '', time()-3600);

header("Location:index.php");
