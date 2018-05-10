<?php namespace SKYOJ\Admin;

function judge_profileHandle()
{
    global $SkyOJ,$_E;
    $profiles = [];
    $param = $SkyOJ->UriParam(3);

    switch($param)
    {
        case 'new':
            break;
        case 'list':
            foreach( \SkyOJ\Judge\JudgeProfileEnum::getConstants() as $key )
            {
                if( $key == \SkyOJ\Judge\JudgeProfileEnum::None )
                    continue;
                $profiles[] = \SkyOJ\Judge\JudgeProfileEnum::getRowData($key);
            }
            $_E['template']['judge_profiles'] = $profiles; 
            \Render::renderSingleTemplate('admin_judge_profile', 'admin');
            exit();
        default:
            \Render::renderSingleTemplate('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/admin/admin_judge_profile_$param.php";
    $func     = __NAMESPACE__ ."\\admin_judge_profile_{$param}Handle";

    require_once($funcpath);
    $func();
}
