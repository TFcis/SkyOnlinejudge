<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'class_zjcore.inc.php';
class class_cf
{
    public $version = '1.0';
    public $name = 'CF capturer';
    public $description = 'Codeforces capturer';
    public $copyright = 'by xiplus';
    public $pattern = '/^cf:[0-9]+[A-Z]{1}$/i';
    private $zjcore;
    private $html;

    public function __construct()
    {
        $this->zjcore = new zjcore();
        $this->zjcore->websiteurl = 'http://codeforces.com/';
        $this->zjcore->userpage = 'api/user.status?handle=';
        $this->zjcore->classname = 'class_cf';
    }

    public function install()
    {
        $tb = DB::tname('ojlist');
        DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_cf','Codeforces','Account Name',1)");
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
        preg_match('/CF:([0-9]+[A-Z]{1})/i', $pid, $match);
        $pid = $match[1].$match[2];
        $response = $this->html[$uid];
        $result = json_decode($response, true);

        if ($result['status'] != 'OK') {
            return 0;
        } else {
            $result = $result['result'];
            $CF_stats = 0;
            foreach ($result as $temp) {
                if ($temp['problem']['contestId'].$temp['problem']['index'] == $pid) {
                    if ($temp['verdict'] == 'OK') {
                        return 90;
                    } else {
                        $CF_stats = 70;
                    }
                }
            }

            return $CF_stats;
        }
    }

    public function showname($pid)
    {
        preg_match('/CF:([0-9]+)([A-Z]{1})/i', $pid, $match);
        $pid = $match[1].$match[2];

        return "<a href='http://codeforces.com/problemset/problem/".$match[1].'/'.$match[2]."' target='_blank'>CF $pid</a>";
    }

    public function challink($uid, $pid)
    {
        return '';
    }
}
