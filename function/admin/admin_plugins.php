<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
//URL Format
// /plugin/list?folder=
// /plugin/install/forder/name/
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
            $_E['template']['folder'] = $folder;
            $_E['template']['class'] = $class;
            Render::renderSingleTemplate('admin_plugins_funccheckfail','admin');
            exit(0);
        }
            
        
        $_E['template']['message'] = 'On Working~';
    default:
        break;
}
Log::msg(Level::Waring,"[admin][plugins]Error dump",$QUEST);
Render::renderSingleTemplate('common_message');