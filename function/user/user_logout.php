<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
userControl::DelLoginToken();
setcookie('uid','',0);
header("Location:index.php");
