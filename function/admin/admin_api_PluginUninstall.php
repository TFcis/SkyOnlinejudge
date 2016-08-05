<?php namespace SKYOJ\Admin;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function admin_api_PluginUninstallHandle()
{
    $folder = \SKYOJ\safe_post('folder');
    $class  = \SKYOJ\safe_post('class');
    $msg = "未知的錯誤";
    try{
        if( \Plugin::loadClassFile($folder, $class) === false )
            \SKYOJ\throwjson('error', 'Load Class File Error!');
        \Plugin::uninstallClass($class);
    }catch(\Exception $e){
        \Log::msg(\Level::Error,'Admin Uninstall Plugin '.$folder.' '.$class.'ERROR!',$e);
        \SKYOJ\throwjson('error', '解除安裝失敗!'.$e->getMessage());
    }
    \SKYOJ\throwjson('SUCC', 'Install Class Success!');
}
