<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'class_zjcore.inc.php';
class class_tcgs
{
    public $version = '1.0';
    public $name = 'TCGS capturer';
    public $description = 'TCGS Zerojudge (Green Judge) capturer';
    public $copyright = 'by Domen';
    public $pattern = '/^(tcgs|GJ):[a-z]{1}[0-9]+$/i';
    private $zjcore;

    public function __construct()
    {
        $this->zjcore = new zjcore();
        $this->zjcore->websiteurl = 'http://www.tcgs.tc.edu.tw:1218/';
        $this->zjcore->classname = 'class_tcgs';
        $this->zjcore->userpage = 'ShowUserStatistic?account=';
    }

    public function install()
    {
        $tb = DB::tname('ojlist');
        DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_tcgs','Green Judge(台中女中ZJ)','Account Name',1)");
    }

    public function checkid($id)
    {
        return $this->zjcore->checkid($id);
    }

    public function preprocess($userlist, $problems)
    {
        global $_E;
        $this->zjcore->preprocess($userlist, $problems);
    }

    public function query($uid, $pid)
    {
        global $_E;
        $pid = $this->zjcore->reg_problemid($pid);

        return $this->zjcore->query($uid, $pid);
    }

    public function showname($pid)
    {
        $pname = $this->zjcore->reg_problemid($pid);

        return "<a href='http://www.tcgs.tc.edu.tw:1218/ShowProblem?problemid=$pname' target='_blank'>GJ $pname</a>";
    }

    public function challink($uid, $pid)
    {
        $pname = $this->zjcore->reg_problemid($pid);

        return 'http://www.tcgs.tc.edu.tw:1218/RealtimeStatus?problemid='.$pname.'&account='.$uid;
    }
}
