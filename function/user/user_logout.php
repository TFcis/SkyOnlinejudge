<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
function logoutHandle()
{
    global $_G,$_E,$_config;
    if ($_G['uid']) {
        \userControl::DelLoginToken();
        \userControl::RemoveCookie('uid');
    }
    header('Location:'.$_E['SITEROOT'].'index.php');
}

