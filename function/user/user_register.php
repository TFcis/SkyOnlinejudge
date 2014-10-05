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
    $checkrule = isset($_REQUEST['accept']) ? $_REQUEST['accept'] : 'null' ;
    switch($checkrule)
    {
        case 'null':
            $Render->render('user_register_check','user');
            break;
        case 'accept':
            $Render->render('user_register_form','user');
            break;
        case 'reg':
            if( isset($_POST['accept']) &&
                register($_POST['email'],$_POST['nickname'],$_POST['password'],$_POST['repeat']))
            {
                // need better page
                header("Location:user.php");
            }
            else
            {
                $Render->render('user_register_form','user');
            }
            
            break;
        default:
            $Render->render('nonedefined');
            break;
    }
}
else //First visit register page. Give him a taken.
{
    $registerToken = uniqid('',true);
    $timeout = time()+600;
    $_SESSION['REGTOKEN'][$registerToken]=array( 'timestamp' => $timeout );
    setcookie('REGACCEPTABLE',$registerToken,$timeout);
    $Render->render('user_register_check','user');
}