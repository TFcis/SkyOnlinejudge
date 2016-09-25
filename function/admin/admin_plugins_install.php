<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
// /plugin/install/base64(forder)/base64(name)/[key]
function admin_plugins_installHandle()
{
    global $SkyOJ,$_G,$_E;

    try{
        $folder = base64_decode($SkyOJ->UriParam(4));
        $class  = base64_decode($SkyOJ->UriParam(5));
        $key = \SKYOJ\safe_get('key');

        if( $folder===false || $class===false )
            throw new \Exception();
        
        //TODO : Dose loadClassFile check $class??
        if( \Plugin::loadClassFile($folder, $class) === false )
            throw new \Exception('無法載入，請檢查系統紀錄');

        $_E['template']['folder'] = $folder;
        $_E['template']['class'] = $class;

        //Installed??

        //Check Required Function First Always
        $fail = [];
        foreach( $class::requiredFunctions() as $func ){
            if( !function_exists($func) ){
                $fail[] = $func;
            }
        }
        if( !empty($fail) ){
            $_E['template']['fail_func'] = $fail;
            \Render::renderSingleTemplate('admin_plugins_funccheckfail', 'admin');
            exit(0);
        }

        $tokenname = 'admin_plugins_install_'.md5($folder.'#'.$class);
        if( !isset($key) || $key !== \userControl::getSavedToken($tokenname) ){//licence
            $_E['template']['licence'] = $class::licence_tmpl();
            $_E['template']['key'] = \userControl::RegisterToken($tokenname, 900, false);
            \Render::renderSingleTemplate('admin_plugins_licence', 'admin');
            exit(0);
        }
        $formInfo = $class::installForm();
        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'token','value'=>$_E['template']['ADMIN_CSRF']]);
        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'class','value'=>$class]);
        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'folder','value'=>$folder]);
        $formInfo['data'][] = new \SKYOJ\HTML_INPUT_BUTTOM(['name'=>'btn','title' => '送出','option'=>['help_text'=>'true']]);
        
        $_E['template']['pif_install'] = new \SKYOJ\FormInfo($formInfo);
        \Render::renderSingleTemplate('admin_plugins_install_form', 'admin');
        exit(0);
    }catch(\Exception $e){
        $_E['template']['message'] = $e->getMessage();
        \Render::renderSingleTemplate('nonedefined');
        exit(0);
    }
    \Render::renderSingleTemplate('nonedefined');
}