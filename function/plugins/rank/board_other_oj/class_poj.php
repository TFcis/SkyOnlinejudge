<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'class_zjcore.inc.php';
class class_poj
{
    public $version = '1.0';
    public $name = 'POJ capturer';
    public $description = 'PKU Online Judge capturer';
    public $copyright = 'by domen111';
    public $pattern = '/^poj[0-9]+$/i';
    private $zjcore;
    private $html;

    public function __construct()
    {
        $this->zjcore = new zjcore();
        $this->zjcore->websiteurl = 'http://poj.org/';
        $this->zjcore->userpage = 'usercmp?';
        $this->zjcore->classname = 'class_poj';
        $this->zjcore->userpagepattern = '$WEB.$USERPAGE.\'uid1=\'.$user.\'&uid2=\'.$user';
    }

    public function install()
    {
        $tb = DB::tname('ojlist');
        DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_poj','北京大學POJ','Account Name',1)");
    }

    public function checkid($id)
    {
        return $this->zjcore->checkid($id);
    }

    public function preprocess($userlist, $problems)
    {
        global $_E;
        $this->zjcore->preprocess($userlist, $problems);
        $this->html = $this->zjcore->html_sumary;
    }

    public function query($uid, $pid)
    {
        global $_E;
        $pid = preg_replace('/[^0-9]+/', '', $pid);
        $response = $this->html[$uid];
        if (!$response) {
            return 0;
        }
        $html = str_replace("\n", '', $response);

        $POJ_stats = 0;
        preg_match('/Problems both.*?accepted(.+?)Problems only.*?tried but failed/', $html, $match);
        if (strpos($match[1], $pid) != false) {
            return 90;
        }
        preg_match('/Problems both.*?tried but failed(.+?)Home Page/', $html, $match);
        if (strpos($match[1], $pid) != false) {
            return 70;
        }

        return $POJ_stats;
    }

    public function showname($pid)
    {
        $pid = preg_replace('/[^0-9]+/', '', $pid);

        return "<a href='http://poj.org/problem?id=$pid' target='_blank'>POJ $pid</a>";
    }

    public function challink($uid, $pid)
    {
        $pid = preg_replace('/[^0-9]+/', '', $pid);

        return "http://poj.org/status?problem_id=$pid&user_id=$uid&result=&language=";
    }
}
