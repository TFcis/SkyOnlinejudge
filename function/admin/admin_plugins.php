<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function pluginsHandle()
{
    global $SkyOJ,$_G,$_E;
    //URL Format
    // /plugin/list?folder=
    // /plugin/install/base64(forder)/base64(name)?= stats
    $param = $SkyOJ->UriParam(3);
    switch($param)
    {
        case 'list':
            $all_folders = \Plugin::getAllFolders();
            $folder = \SKYOJ\safe_get('folder');
            $data = null;

            if (in_array($folder, $all_folders)) {
                $classes = \Plugin::loadClassFileByFolder($folder);
                $data = \Plugin::checkInstall($classes);
            } else {
                $folder = '[No Such Folder]';
            }
            $_E['template']['sysplugins'][$folder] = $data ? $data : [];
            \Render::renderSingleTemplate('admin_plugins', 'admin');
            exit(0);
        case 'install':
                break;
        default:
                \Render::renderSingleTemplate('nonedefined');
                exit(0);
            break;
    }
    $funcpath = $_E['ROOT']."/function/admin/admin_plugins_$param.php";
    $func     = __NAMESPACE__ ."\\admin_plugins_{$param}Handle";

    require_once($funcpath);
    $func();
}
