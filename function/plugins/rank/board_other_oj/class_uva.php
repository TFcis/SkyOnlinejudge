<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class class_uva
{
    public $version = '1.1';
    public $name = 'UVa capturer';
    public $description = 'UVa capturer';
    public $copyright = 'test by Domen';
    public $pattern = '/^uva[0-9]+$/i';
    private $rate = [];

    public function install()
    {
        $tb = DB::tname('ojlist');
        DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_uva','UVa Online Judge','UVa user name',1)");
        //set SQL
    }

    public function checkid($uname)
    {
        $uname = (string) $uname;
        if (!preg_match('/[\da-zA-Z_]{2,}/', $uname)) { //No spaces, at least 2 characters and contain 0-9,a-z,A-Z
            return false;
        }
        if (!$this->uname2id($uname)) {
            return false;
        }

        return true;
    }

    public function realpname($pname)
    {
        return preg_replace('/[^0-9]*/', '', $pname);
    }

    public function uname2id($uname)
    {
        static $data = null;
        if ($data === null) {
            $data = DB::loadcache('class_uva_uname2id');
            if ($data === false) {
                $data = [];
            }
        }

        if (!isset($data[$uname])) {
            $uid = @file_get_contents("http://uhunt.felix-halim.net/api/uname2uid/$uname");
            if ($uid == '0') {
                return false;
            }
            $data[$uname] = intval($uid);
            DB::putcache('class_uva_uname2id', $data, 'forever');
        }

        return $data[$uname];
    }

    public function probId2Num($pid)
    {
        static $data = null;
        if ($data === null) {
            $data = DB::loadcache('class_uva_probId2Num');
            if ($data === false) {
                $data = [];
            }
        }
        $pid = $this->realpname($pid);

        if (!isset($data[$pid])) {
            $pnum = @file_get_contents("http://uhunt.felix-halim.net/api/p/id/$pid");
            $pnum = json_decode($pnum, true);
            if ($pnum == false) {
                return false;
            }
            $pnum = intval($pnum['num']);
            $data[$pid] = $pnum;
            DB::putcache('class_uva_probId2Num', $data, 'forever'); //todo forever
        }

        return $data[$pid];
    }

    public function preprocess($userlist, $problist)
    {
        foreach ($userlist as &$user) {
            $user = $this->uname2id($user);
        }
        foreach ($problist as &$pnum) {
            $pnum = $this->realpname($pnum);
        }

        //fetch
        $data = file_get_contents('http://uhunt.felix-halim.net/api/subs-nums/'.implode(',', $userlist).'/'.implode(',', $problist).'/0');
        if ($data === false) {
            return;
        }
        $data = json_decode($data, true);

        foreach ($userlist as $user) {
            $uid = intval($user);
            $udata = $data[$uid]['subs'];
            $verdict = [];
            foreach ($udata as $sub) {
                if ($sub[2] != 20) {
                    $pnum = $this->probId2Num($sub[1]);
                    if (!isset($verdict[$pnum])) {
                        $verdict[$pnum] = 0;
                    }
                    $verdict[$pnum] = max($verdict[$pnum], $sub[2]);
                }
            }
            if (!isset($this->rate[$uid])) {
                $this->rate[$uid] = [];
            }
            foreach ($verdict as $p => $v) {
                $this->rate[$uid][$p] = $v;
            }
        }
    }

    public function query($uid, $pnum)
    {
        $pnum = preg_replace('/[^0-9]*/', '', $pnum);
        $uid = $this->uname2id($uid);
        $pnum = $this->realpname($pnum);

        if (!isset($this->rate[$uid])) {
            return 0;
        }

        if (!isset($this->rate[$uid][$pnum])) {
            return 0;
        }

        $data = $this->rate[$uid][$pnum];
        if ($data == '90') {
            return 90;
        } else {
            return 70;
        }
    }

    public function showname($str)
    {
        $pnum = preg_replace('/[^0-9]*/', '', $str);
        $url = "http://domen.twbbs.org/uva/?$pnum";
        $str = "<a href='$url' target='_blank'>UVa $pnum</a>";

        return $str;
    }

    public function challink($uid, $pid)
    {
        $uid = $this->uname2id($uid);

        return "http://uhunt.felix-halim.net/id/$uid";
    }
}
