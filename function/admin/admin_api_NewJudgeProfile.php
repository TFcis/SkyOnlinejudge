<?php namespace SKYOJ\Admin;

use \SkyOJ\Judge\JudgeTypeEnum;
use \SkyOJ\Judge\JudgeProfileEnum;

function admin_api_NewJudgeProfileHandle()
{
    $msg = "未知的錯誤";
    try
    {
        $data = $_POST;
        $judge_type = (int)\SKYOJ\safe_post('judge_type');
        $profile_name = \SKYOJ\safe_post('profile_name');

        if( !JudgeTypeEnum::isValidValue($judge_type) || $judge_type == JudgeTypeEnum::None )
            \SKYOJ\throwjson('error', 'No Such Judge');
        
        $class = '\\SkyOJ\\Judge\\'.JudgeTypeEnum::str($judge_type);

        $profile = $class::checkProfile($data, $msg);
        if( $profile === false )
            throw new \Exception($msg);
        JudgeProfileEnum::create($profile_name,$judge_type,$profile);
        //Add this profile
    }
    catch(\Exception $e)
    {
        \Log::msg(\Level::Error,'Admin Install Plugin '.' '.$class.'ERROR!',$e);
        \SKYOJ\throwjson('error', '安裝失敗!'.$e->getMessage());
    }
    \SKYOJ\throwjson('SUCC', 'Install Class Success!');
}
