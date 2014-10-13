<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
$showid = $_G['uid'];

if( isset($_GET['id']) )
{
    $tid = $_GET['id'];
    if( is_numeric ($tid) )
    {
        $showid = $tid;
    }
    else
    {
         $_E['template']['alert'] = 'WTF!?';
    }
}

$_E['template']['avaterurl'] = "http://www.gravatar.com/avatar/$showid?d=identicon&s=400";
Render::render('user_view','user');