<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

#Check Session
if( !isset($_SESSION['REGTOKEN']) )
{
    $_SESSION['REGTOKEN'] = array();
}
$registerToken = '';

#GetCookie
if( isset($_COOKIE['REGACCEPTABLE']) )
{
    $registerToken = $_COOKIE['REGACCEPTABLE'];
}

if( $registerToken 
    && isset($_SESSION['REGTOKEN'][$registerToken])
    && $_SESSION['REGTOKEN'][$registerToken]['timestamp']>time() )
{
    $checkrule = isset($_GET['accept']) ? $_GET['accept'] : 'null' ;
    switch($checkrule)
    {
        case 'null':
            render('user/user_register_check');
            break;
        case 'accept':
            render('nonedefined');
            //render('user/user_register_form');
            break;
        case 'reg':
            render('nonedefined');
            break;
        default:
            render('nonedefined');
            break;
    }
}
else //First visit register page. Give him a taken.
{
    $registerToken = uniqid('',true);
    $timeout = time()+600;
    $_SESSION['REGTOKEN'][$registerToken]=array( 'timestamp' => $timeout );
    setcookie('REGACCEPTABLE',$registerToken,$timeout);
    
    render('user/user_register_check');
}