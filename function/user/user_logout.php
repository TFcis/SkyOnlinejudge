<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
if($_G['uid'])
{
    userControl::DelLoginToken();
    setcookie('uid','',0);
}
header("Location:".$_E['SITEROOT']."index.php");
