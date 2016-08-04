<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function edit_quoteHandle(UserInfo $userInfo)
{
    $olddata = $userInfo->load_data('view');

    $quote = \SKYOJ\safe_post('quote');
    $quote_ref = \SKYOJ\safe_post('quote_ref');
    if ( !isset($quote,$quote_ref) ) {
        \SKYOJ\throwjson('error', 'data missing');
    }

    if (($s = strlen($quote)) > 350) {
        \SKYOJ\throwjson('error', "Quote too long!($s)");
    }
    if (($s = strlen($quote_ref)) > 80) {
        \SKYOJ\throwjson('error', "Quote ref too long!($s)");
    }

    $olddata['quote'] = $quote;
    $olddata['quote_ref'] = $quote_ref;
    
    if ($userInfo->save_data('view', $olddata)) {
        \SKYOJ\throwjson('SUCC', 'SUCC');
    } else {
        \SKYOJ\throwjson('error', 'Something error...');
    }
}