<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
require_once($_E['ROOT'].'/function/common/forminfo.php');
//URL Format
// /plugin/list?folder=
// /plugin/install/base64(forder)/base64(name)?= stats
$function = Quest(1) or $function = '';
switch($function)
{
    case 'list':
        $all_folders = Plugin::getAllFolders();
        $folder = safe_get('folder');
        $data = null;
        if( in_array($folder,$all_folders) )
        {
            $classes = Plugin::loadClassFileByFolder($folder);
            $data    = Plugin::checkInstall($classes);
        }
        else
        {
            $folder = '[No Such Folder]';
        }
        $_E['template']['sysplugins'][$folder] = $data?$data:[];
        Render::renderSingleTemplate('admin_plugins','admin');
        exit(0);
        break;

    case 'install':
        $folder = base64_decode(Quest(2));
        $class  = base64_decode(Quest(3));

        if( $folder===false || $class===false )
        {
            break;
        }
        if( Plugin::loadClassFile($folder,$class) === false )
        {
            $_E['template']['message'] = "無法載入，請檢查系統紀錄";
            break;
        }
        $_E['template']['folder'] = $folder;
        $_E['template']['class'] = $class;

        //Check Required functions
        $fail = [];
        foreach( $class::requiredFunctions() as $func )
        {
            if( !function_exists($func) )
            {
                $fail[] = $func;
            }
        }
        if( !empty($fail) )
        {
            $_E['template']['fail_func'] = $fail;
            Render::renderSingleTemplate('admin_plugins_funccheckfail','admin');
            exit(0);
        }
        
        //Licence
        $tokenname = 'admin_pi_'.md5($folder."#".$class);
        if( !userControl::CheckToken($tokenname) ||
            !userControl::isToeknSet($tokenname."#key") ||
            safe_get('key') !== userControl::getSavedToken( $tokenname."#key" ) )
        {
            userControl::RegisterToken( $tokenname,900 );
            $key = userControl::RegisterToken( $tokenname."#key",900,false );
            $_E['template']['licence'] = $class::licence_tmpl();
            $_E['template']['key'] = $key;
            Render::renderSingleTemplate('admin_plugins_licence','admin');
            exit(0);
        }


        //Form
        $tokenname = 'admin_pif_'.md5($folder."#".$class);
        $formInfo = $class::installForm();
        if( !empty($formInfo) )
        {
            $_E['template']['pif_install'] = new FormInfo($formInfo);
            Render::renderSingleTemplate('admin_plugins_install_form','admin');
            exit(0);
        }
        else
        {
        }
        //install();
        $_E['template']['message'] = 'On Working~';
        #userControl::DeleteToken($tokenname);
        #userControl::DeleteToken($tokenname."#key");
    default:
        break;
}
Log::msg(Level::Waring,"[admin][plugins]Error dump",$QUEST);
Render::renderSingleTemplate('common_message');
