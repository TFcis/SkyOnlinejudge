<?php namespace SKYOJ;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//BASIC
function NeverReach()
{
    throw new \Exception('NeverReach Code!');
}

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
            return null;
        }
    }

    return null;
}

function safe_post($key, $usearray = false)
{
    if (isset($_POST[$key])) {
        if (is_array($_POST[$key]) == $usearray) {
            return $_POST[$key];
        } else {
            return null;
        }
    }

    return null;
}

/**
 * check if $val can be turn to c int format
 * string :
 *   0236  :false
 *   123   :true
 *   1321654165161651 : false( if php int cannot support it )
 * int :
 *   why check this?
 * others :
 *   false
 */
function check_tocint($val):bool
{
    if( is_int($val) )
    {
        return true;
    }
    if( is_string($val) )
    {
        if( ctype_digit($val) && ($val==='0'||$val[0]!=='0') )
            return true;
    }
    return false;
}

function check_totimestamp($val,&$conv = null):bool
{
    static $days = [0,31,29,31,30,31,30,31,31,30,31,30,31];
    if( !is_string($val) )
    {
        return false;
    }
    $d = sscanf($val,"%d-%d-%d %d:%d:%d");
    if( !is_array($d) || count($d) != 6 )
    {
        return false;
    }
    
    if( $d[0]<2000 )return false; //year
    if( $d[1]<1 || 12<$d[1] )return false; //month
    if( $d[2]<1 || $days[$d[1]]<$d[2] )return false; //day

    if( $d[3]<0 || 24<$d[3] )return false; //hour
    if( $d[4]<0 || 60<$d[4] )return false; //minute
    if( $d[5]<0 || 60<$d[5] )return false; //second
    $conv = vsprintf("%04d-%02d-%02d %02d:%02d:%02d",$d);
    return true;
        
}
function safe_post_int(string $key)
{
    $data = safe_post($key);
    if( !isset($data) || empty($data) )
    {
        return null;
    }
    if( !check_tocint($data) )
    {
        throw new \Exception('safe_post_int for ['.$key.'] failed!');
        return null;
    }
    return (int)$data;
}

function safe_get_int(string $key)
{
    $data = safe_get($key);
    if( !isset($data) || empty($data) )
    {
        return null;
    }
    if( !check_tocint($data) )
    {
        throw new \Exception('safe_get_int for ['.$key.'] failed!');
        return null;
    }
    return (int)$data;
}

//TODO use ?int when upgrade to PHP7.1
function get_timestamp(int $time):string
{
     return date('Y-m-d G:i:s',$time);
}

function CreateFolder(string $path,bool $rewrite = false,bool $recursive = false):bool
{
    if( !$rewrite && file_exists($path) )
        return false;
    if( file_exists($path) && !is_dir($path) )
        return false;
    return mkdir($path,0777,$recursive);
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
    $res = \userControl::getuserdata('account',$uid,['uid','nickname']);
    foreach ($uid as $u) {
        $u = (string) $u;
        if (isset($res[$u])) {
            $_E['nickname'][$u] = $res[$u]['nickname'];
        }
    }
    $_E['nickname']['0'] = 'anonymous';
    return $_E['nickname'];
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

class RESULTCODE extends BasicEnum
{
    const WAIT = 0;
    const JUDGING = 10;
    const AC = 20;
    const PE = 25;
    const WA = 30;
    const RE = 40;
    const TLE= 50;
    const MLE= 60;
    const CE = 70;
    const JE = 80;
}

function getresulttext($resultid)
{
    $res = '';
    switch ($resultid) {
        case RESULTCODE::WAIT:      $res = 'WAIT'; break;
        case RESULTCODE::JUDGING:   $res = 'NONE'; break;
        case RESULTCODE::AC:        $res = 'AC'; break;
        case RESULTCODE::PE:        $res = 'PE'; break;
        case RESULTCODE::WA:        $res = 'WA'; break;
        case RESULTCODE::RE:        $res = 'RE'; break;
        case RESULTCODE::TLE:       $res = 'TLE'; break;
        case RESULTCODE::MLE:       $res = 'MLE'; break;
        case RESULTCODE::CE:        $res = 'CE'; break;
        case RESULTCODE::JE:        $res = 'JE'; break;
    }
    return $res;
}

function getresulttexthtml($resultid,bool $simple = false)
{
    $mini = getresulttext($resultid);
    switch ($resultid) {
        case RESULTCODE::WAIT:      $res = 'Waiting'; break;
        case RESULTCODE::JUDGING:   $res = 'Challenging'; break;
        case RESULTCODE::AC:        $res = 'Accepted'; break;
        case RESULTCODE::WA:        $res = 'Wrong Answer'; break;
        case RESULTCODE::RE:        $res = 'Runtime Error'; break;
        case RESULTCODE::TLE:       $res = 'Time Limit Exceed'; break;
        case RESULTCODE::MLE:       $res = 'Memory Limit Exceed'; break;
        case RESULTCODE::CE:        $res = 'Compile Error'; break;
        case RESULTCODE::JE:        $res = 'Judge Error'; break;
        default:                    $res = $mini; break;
    }
    if( $simple )
        return "<span class='{$mini}' data-res='{$mini}'>{$mini}</span>";
    else
        return "<span class='hidden-xs hidden-sm {$mini}' data-res='{$mini}'>{$res}</span>".
               "<span class='visible-xs-inline visible-sm-inline {$mini}' data-res='{$mini}'>{$mini}</span>";
}

function html(string $str):string
{
    return htmlentities($str,ENT_HTML5|ENT_COMPAT,"UTF-8");
}