<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

if( $_G['uid'] )
{
    Render::ShowMessage("你不是登入了!?");
    exit('');
}

if( userControl::LoadDataByNamespace('register') )
{
    $checkrule = isset($_REQUEST['accept']) ? $_REQUEST['accept'] : 'null' ;
    switch($checkrule)
    {
        case 'null':
            Render::render('user_register_check','user');
            break;
        case 'accept':
            Render::render('user_register_form','user');
            break;
        case 'reg':
            if( isset($_POST['accept']) &&
                register($_POST['email'],$_POST['nickname'],$_POST['password'],$_POST['repeat']))
            {
                // need better page
                DeleteDataByNamespace('register');
                header("Location:user.php");
            }
            else
            {
                Render::render('user_register_form','user');
            }
            
            break;
        default:
            Render::render('nonedefined');
            break;
    }
}
else //First visit register page. Give him a taken.
{
    setcookie('uid','0',time()+300);
    userControl::RegisterTokenInNamespace('register',300);
    Render::render('user_register_check','user');
}