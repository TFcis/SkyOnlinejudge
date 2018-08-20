<?php namespace SKYOJ\Admin;

function admin_ojcapture_profile_newHandle()
{
    global $SkyOJ,$_G,$_E;

    try
    {
        $ojcapture = $SkyOJ->UriParam(4);

        if( !$ojcapture )
        {
            $ojcaptures = \SkyOJ\Scoreboard\OJCaptureEnum::getConstants();
            unset( $ojcaptures[\SkyOJ\Scoreboard\OJCaptureEnum::str(\SkyOJ\Scoreboard\OJCaptureEnum::None)] );
            $_E['template']['ojcaptures'] = $ojcaptures;
            \Render::renderSingleTemplate('admin_ojcapture_profile_new_selectojcapture', 'admin');
        }
        else
        {
            if( !\SkyOJ\Scoreboard\OJCaptureEnum::isValidValue((int)$ojcapture) )
                throw new \Exception('no such judge');
            $class = '\\SkyOJ\\Scoreboard\\Plugin\\'.\SkyOJ\Scoreboard\OJCaptureEnum::str((int)$ojcapture);

            $formInfo = $class::installForm();
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_TEXT  (['name'=>'profile_name','required'=>'required','option'=>['help_text'=>'Profile Name']]);
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'ojcapture','value' => $ojcapture]);
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'token','value'=>$_E['template']['ADMIN_CSRF']]);
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_BUTTOM(['name'=>'btn','title' => '送出','option'=>['help_text'=>'true']]);
            $_E['template']['formInfo'] = new \SKYOJ\FormInfo($formInfo);
            

            \Render::renderSingleTemplate('admin_ojcapture_profile_install_form', 'admin');
        }
        exit(0);
    }
    catch(\Exception $e)
    {
        $_E['template']['message'] = $e->getMessage();
        \Render::renderSingleTemplate('nonedefined');
        exit(0);
    }
}