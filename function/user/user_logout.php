<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
if ($_G['uid']) {
    userControl::DelLoginToken();
    setcookie($_config['cookie']['namepre'].'_uid', '', 0, $_E['SITEDIR']);
}
header('Location:'.$_E['SITEROOT'].'index.php');
