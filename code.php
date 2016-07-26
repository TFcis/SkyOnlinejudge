<?php namespace SKYOJ\Code;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once 'function/code/code.lib.php';
function CodeHandle()
{
    global $SkyOJ,$_E;
    $param = $SkyOJ->UriParam(1)??'codepad';
    if( $_E['Codepad']['enabled'] == false )
    {
        \Render::ShowMessage('Codepad Closed QQ');
        exit(0);
    }
    switch( $param )
    {
        case 'codepad':
        case 'view':
            break;

            //api
        case 'submit':
            break;
        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/code/code_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();
}
