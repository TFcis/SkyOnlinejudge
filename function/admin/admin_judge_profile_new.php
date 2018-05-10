<?php namespace SKYOJ\Admin;

use \SkyOJ\Judge\JudgeTypeEnum;

function admin_judge_profile_newHandle()
{
    global $SkyOJ,$_G,$_E;

    try
    {
        $judge = $SkyOJ->UriParam(4);

        if( !$judge )
        {
            $judges = JudgeTypeEnum::getConstants();
            unset( $judges[JudgeTypeEnum::str(JudgeTypeEnum::None)] );
            $_E['template']['judges'] = $judges;
            \Render::renderSingleTemplate('admin_judge_profile_new_selectjudge', 'admin');
        }
        else
        {
            if( !JudgeTypeEnum::isValidValue((int)$judge) )
                throw new \Exception('no such judge');
            $class = '\\SkyOJ\\Judge\\'.JudgeTypeEnum::str((int)$judge);

            $formInfo = $class::installForm();
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_TEXT  (['name'=>'profile_name','required'=>'required','option'=>['help_text'=>'Profile Name']]);
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'judge_type','value' => $judge]);
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_HIDDEN(['name'=>'token','value'=>$_E['template']['ADMIN_CSRF']]);
            $formInfo['data'][] = new \SKYOJ\HTML_INPUT_BUTTOM(['name'=>'btn','title' => '送出','option'=>['help_text'=>'true']]);
            $_E['template']['formInfo'] = new \SKYOJ\FormInfo($formInfo);
            

            \Render::renderSingleTemplate('admin_judge_profile_install_form', 'admin');
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