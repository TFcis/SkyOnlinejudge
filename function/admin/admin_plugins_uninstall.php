<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
// /plugin/install/base64(forder)/base64(name)/[key]
function admin_plugins_uninstallHandle()
{
    global $SkyOJ,$_G,$_E;

    try{
        $folder = base64_decode($SkyOJ->UriParam(4));
        $class  = base64_decode($SkyOJ->UriParam(5));

        if( $folder===false || $class===false )
            throw new \Exception();
        
        //TODO : Dose loadClassFile check $class??
        if( \Plugin::loadClassFile($folder, $class) === false )
            throw new \Exception('無法載入，請檢查系統紀錄');

        $_E['template']['folder'] = $folder;
        $_E['template']['class'] = $class;

        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'token','value'=>$_E['template']['ADMIN_CSRF']]);
        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'class','value'=>$class]);
        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'folder','value'=>$folder]);
        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'確認','option'=>['help_text'=>'true']]);
        
        $_E['template']['pif_uninstall'] = new \SKYOJ\FormInfo($formInfo);
        \Render::renderSingleTemplate('admin_plugins_uninstall_form', 'admin');
    }catch(\Exception $e){
        $_E['template']['message'] = $e->getMessage();
        \Render::renderSingleTemplate('nonedefined');
        exit(0);
    }
}