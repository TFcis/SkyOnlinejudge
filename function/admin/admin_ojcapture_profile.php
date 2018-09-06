<?php namespace SKYOJ\Admin;

function ojcapture_profileHandle()
{
    global $SkyOJ,$_E;
    $profiles = [];
    $param = $SkyOJ->UriParam(3);

    switch($param)
    {
        case 'new':
            break;
        case 'list':
            foreach( \SkyOJ\Scoreboard\OJCaptureProfileEnum::getConstants() as $key )
            {
                if( $key == \SkyOJ\Scoreboard\OJCaptureProfileEnum::None )
                    continue;
                $profiles[] = \SkyOJ\Scoreboard\OJCaptureProfileEnum::getRowData($key);
            }
            $_E['template']['ojcapture_profiles'] = $profiles; 
            \Render::renderSingleTemplate('admin_ojcapture_profile', 'admin');
            exit();
        default:
            \Render::renderSingleTemplate('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/admin/admin_ojcapture_profile_$param.php";
    $func     = __NAMESPACE__ ."\\admin_ojcapture_profile_{$param}Handle";

    require_once($funcpath);
    $func();
}
