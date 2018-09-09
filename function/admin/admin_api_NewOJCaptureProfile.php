<?php namespace SKYOJ\Admin;

function admin_api_NewOJCaptureProfileHandle()
{
    $msg = "未知的錯誤";
    try
    {
        $data = $_POST;
        $ojcapture = (int)\SKYOJ\safe_post('ojcapture');
        $profile_name = \SKYOJ\safe_post('profile_name');

        if( !\SkyOJ\Scoreboard\OJCaptureEnum::isValidValue($ojcapture) || $ojcapture == \SkyOJ\Scoreboard\OJCaptureEnum::None )
            \SKYOJ\throwjson('error', 'No Such Judge');

        $class = '\\SkyOJ\\Scoreboard\\Plugin\\'.\SkyOJ\Scoreboard\OJCaptureEnum::str($ojcapture);

        $profile = $class::checkProfile($data, $msg);
        if( $profile === false )
            throw new \Exception($msg);

        \SkyOJ\Scoreboard\OJCaptureProfileEnum::create($profile_name,$ojcapture,$profile);
        \SKYOJ\throwjson('SUCC', 'Install Class Success!');
    }
    catch(\Exception $e)
    {
        \Log::msg(\Level::Error,'Admin Install Plugin '.' '.$class.'ERROR!',$e);
        \SKYOJ\throwjson('error', '安裝失敗!'.$e->getMessage());
    }
    
}
