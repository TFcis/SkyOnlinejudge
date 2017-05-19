<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once $_E['ROOT'].'/function/common/pagelist.php';
use  \SKYOJ\PageList;
function userlistHandle(){
    global $_E;

    $page = (int)\SKYOJ\safe_get('page');
    $search_nick = (string)\SKYOJ\safe_get('nickname');
    $_E['template']['userlist'] = [];
    $pl = new PageList('account','`nickname` LIKE '.'\'%'.$search_nick.'%\'');
    $data = $pl->GetPageDataByPage($page,'uid','*');
    if ($data !== false) {
        $_E['template']['userlist'] = $data;
        $_E['template']['userlist_page_list'] = $pl;
        $_E['template']['userlist_now'] = $page;
        $_E['template']['userlist_search'] = $search_nick;
    }
    \Render::renderSingleTemplate('admin_userlist', 'admin');
}
