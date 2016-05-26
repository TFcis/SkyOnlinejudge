<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//BASIC
function throwjson($status, $data)
{
    exit(json_encode(['status' => $status, 'data' => $data]));
}

function safe_get($key, $usearray = false)
{
    if (isset($_GET[$key])) {
        if (is_array($_GET[$key]) == $usearray) {
            return $_GET[$key];
        } else {
            return false;
        }
    }

    return false;
}

function safe_post($key, $usearray = false)
{
    if (isset($_POST[$key])) {
        if (is_array($_POST[$key]) == $usearray) {
            return $_POST[$key];
        } else {
            return false;
        }
    }

    return false;
}

function Quest(int $id)
{
    global $QUEST;
    if (isset($QUEST[$id])) {
        return $QUEST[$id];
    }

    return false;
}

function make_int($var, int $fail = 0)
{
    if (is_int($var)) {
        return $var;
    }
    if (preg_match("/^\d+$/", $var)) {
        return intval($var);
    }

    return $fail;
}
function extend_userlist($string)
{
    $tmp = explode(',', $string);
    $users = [];
    foreach ($tmp as $user) {
        $res = [];
        $user = trim($user);
        $flag = 'add';
        if ($user === '') {
            continue;
        }
        if ($user[0] === '^') {
            $flag = 'remove';
            $user = preg_replace('/^\^/', '', $user);
        }

        if (is_numeric($user)) {
            $res[] = intval($user);
        } elseif (preg_match('/^(\d+)-(\d+)$/', $user, $match)) {
            $a = intval($match[1]);
            $b = intval($match[2]);
            if ($a && $b) {
                if ($a > $b) {
                    list($a, $b) = [$b, $a];
                }
                for (; $a <= $b; $a++) {
                    $res[] = $a;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        if ($flag == 'add') {
            $users = array_merge($res, $users);
            $users = array_unique($users);
        } else {
            //remove

            foreach ($res as $v) {
                $key = array_search($v, $users);
                if ($key !== false) {
                    unset($users[$key]);
                }
            }
        }
    }
    sort($users);

    return array_unique($users);
}

function extend_problems($problems)
{
    $substr = [];
    $stack = 0;
    $pos = 0;

    $problems = str_replace('*', '', $problems);
    $problems = trim($problems);
    $len = strlen($problems);
    for ($i = 0; $i < $len; ++$i) {
        if ($problems[$i] === '(') {
            if ($stack === 0) {
                $pos = $i;
            }
            $stack++;
        } elseif ($problems[$i] === ')') {
            $stack--;
            if ($stack === 0) {
                if ($i + 1 < $len && $problems[$i + 1] !== ',') {
                    return false;
                }
                $sub = substr($problems, $pos + 1, $i - $pos - 1);
                $substr[] = $sub;
                for (; $pos <= $i; $pos++) {
                    $problems[$pos] = '*';
                }
            }
        }
    }

    $problems = preg_replace('/\*+/', '*', $problems);
    $tmp = explode(',', $problems);
    $subnum = 0;
    $problist = [];

    foreach ($tmp as $word) {
        $res = [];
        $flag = 'add';
        $word = trim($word);
        if (!$word) {
            continue;
        }
        if ($word[0] === '^') {
            $flag = 'remove';
            $word = preg_replace('/^\^/', '', $word);
        }
        if (is_numeric($word[0])) {
            if (is_numeric($word)) {
                $res[] = $word;
            } elseif (preg_match('/^(\d+)-(\d+)$/', $word, $match)) {
                $a = intval($match[1]);
                $b = intval($match[2]);
                if ($a && $b) {
                    if ($a > $b) {
                        list($a, $b) = [$b, $a];
                    }
                    for (; $a <= $b; $a++) {
                        $res[] = (string) $a;
                    }
                } else {
                    return false;
                }
            }
        } else {
            if (strpos($word, '*') === false) {
                $res[] = $word;
            } else {
                $word = str_replace('*', '', $word);
                if ($sb = extend_problems(trim($substr[$subnum++]))) {
                    foreach ($sb as $w) {
                        $res[] = $word.$w;
                    }
                } else {
                    return false;
                }
            }
        }
        if ($flag == 'add') {
            $problist = array_merge($problist, $res);
            $problist = array_unique($problist);
        } else {
            foreach ($res as $v) {
                $key = array_search($v, $problist);
                if ($key !== false) {
                    unset($problist[$key]);
                }
            }
        }
    }

    return array_unique($problist);
}

function envadd($table)
{
    global $_E;
    $_E[$table] = [];
    $tb = DB::tname($table);
    if ($res = DB::query("SELECT * FROM `$tb`")) {
        while ($dat = DB::fetch($res)) {
            $_E[$table][] = $dat;
        }

        return true;
    } else {
        return false;
    }
}
function ojacct_reg($rel, $uid, &$change = null)
{
    if (isset($change)) {
        $change = false;
    }

    global $_E;
    if (!isset($_E['ojlist'])) {
        if (!envadd('ojlist')) {
            return false;
        }
    }

    //做成 id=>classname
    $idToclass = [];
    foreach ($_E['ojlist'] as $row) {
        if ($row['available'] == 1) {
            $idToclass[$row['id']] = $row['class'];
        }
    }
    //移除不存在的OJ
    $oj2 = [];
    foreach ($rel as $row) {
        if (isset($idToclass[$row['id']])) {
            $oj2[$idToclass[$row['id']]] = [
                    'uid'     => $row['uid'],
                    'id'      => $row['id'],
                    'account' => $row['account'],
                    'approve' => $row['approve'],
                    'indexid' => $row['indexid'],
                ];
        } elseif (isset($change)) {
            $change = true;
        }
    }
    //加回空的OJ
    $tmp = [
                    'uid'     => $uid,
                    'id'      => 0,
                    'account' => '',
                    'approve' => 0,
                    'indexid' => '',
                ];
    foreach ($idToclass as $id => $class) {
        if (!isset($oj2[$class])) {
            $oj2[$class] = $tmp;
            $oj2[$class]['id'] = $id;
            $oj2[$class]['indexid'] = $oj2[$class]['uid']."+$id";
            if (isset($change)) {
                $change = true;
            }
        }
    }

    return $oj2;
}

function nickname($uid)
{
    global $_E;
    if (!is_array($uid)) {
        $uid = [$uid];
    }

    $res = usercontrol::getuserdata('account', $uid);
    foreach ($uid as $u) {
        $u = (string) $u;
        if (isset($res[$u])) {
		{
            $_E['nickname'][$u] = $res[$u]['nickname'];
        }
    }
    $_E['nickname']['0'] = 'anonymous';
	
    return $_E['nickname'];
}

function getresulttext($resultid)
{
    $res = 'NO';
    switch ($resultid) {
        case 0: $res = 'NONE'; break;
        case 1: $res = 'AC'; break;
        case 2: $res = 'WA'; break;
        case 3: $res = 'RE'; break;
        case 4: $res = 'TLE'; break;
        case 5: $res = 'MLE'; break;
        case 6: $res = 'CE'; break;
        case 7: $res = 'ERR'; break;
    }
	
    return $res;
}

class privatedata
{
    private $name = null;

    public function __construct()
    {
        global $_E;
        $folder = $_E['ROOT'].'/data/private/';
        $file = '';
        //do{
            $file = md5(uniqid(uniqid())).'.tmp';
        //}while( file_exists($folder. $file)) ;
        $this->name = $folder.$file;
    }

    public function name()
    {
        return $this->name;
    }

    public function __destruct()
    {
        if ($this->name && file_exists($this->name)) {
            unlink($this->name);
        }
    }
}
