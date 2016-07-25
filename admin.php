<?php namespace SKYOJ\Admin;
require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';
require_once 'function/admin/admin.lib.php';
function AdminHandle()
{
    global $_E,$SkyOJ;
    if (!\userControl::isAdmin()) {
        http_response_code(403);
        header('Location: index.php');
        exit(0);
    }

    $param = $SkyOJ->UriParam(1)??'index';
    switch( $param )
    {
        //api
        case 'api':
            break;
        
        //all left should check token!
        case 'index':
            checkToken();
            break;

        //Sub Page
        case 'log':
        case 'plugins':
            checkToken();
            break;

        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/admin/admin_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";
    require_once($funcpath);
    $func();
}

function checkToken()
{
    global $_E;
    $token = \SKYOJ\safe_post('token')??\SKYOJ\safe_get('token');
    if ( \userControl::CheckToken('ADMIN_CSRF',$token)) {
        assert( \userControl::getSavedToken('ADMIN_CSRF') === $token );
        $_E['template']['ADMIN_CSRF'] = $token;
    } else {
        \Render::ShowMessage('Token 已失效，請重新載入'.$token);
        exit(0);
    }
}