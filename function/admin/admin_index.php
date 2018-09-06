<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function indexHandle()
{
    global $_E,$SkyOJ;
    $use_subpage = \SkyOJ\safe_post('subpage') != null;

    if( $use_subpage ){
        $page = $SkyOJ->UriParam(2);
        switch($page)
        {
            case 'log':
            case 'plugins':
            case 'userlist':
            case 'judge_profile':
                break;
            case 'ojcapture_profile':
                break;
            default:
                \Render::renderSingleTemplate('common_message');
                exit(0);
        }
        $funcpath = $_E['ROOT']."/function/admin/admin_$page.php";
        $func     = __NAMESPACE__ ."\\{$page}Handle";
        require_once($funcpath);
        $func();

    }else{
        $_E['template']['pluginfolders'] = \Plugin::getAllFolders();
        \Render::render('admin_main', 'admin');
        exit(0);
    }
}