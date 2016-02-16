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

if( userControl::CheckToken('register') )
{
    $checkrule = isset($_REQUEST['accept']) ? $_REQUEST['accept'] : 'null' ;
    switch($checkrule)
    {
        case 'null':
            Render::setbodyclass('registerbody');
            Render::render('user_register_check','user');
            break;
        case 'accept':
            Render::setbodyclass('registerbody');
            Render::render('user_register_form','user');
            break;
        case 'reg':
            if( isset($_POST['accept']) )
            {
                $res=register(safe_post('email'),
                              safe_post('nickname'),
                              safe_post('password'),
                              safe_post('repeat'));
                // need better page
                if( $res[0] === true )
                {
                    userControl::deletetoken('register');
                    header("Location:user.php");
                    exit(0);
                }
                LOG::msg(Level::Debug,"",$res);
                $_E['template']['reg'] = $res[1];
            }
            Render::setbodyclass('registerbody');
            Render::render('user_register_form','user');
            break;
        default:
            Render::render('nonedefined');
            break;
    }
}
else //First visit register page. Give him a taken.
{
    userControl::SetCookie('uid','0',time()+300);
    userControl::registertoken('register',300);
    Render::setbodyclass('registerbody');
    Render::render('user_register_check','user');
}
