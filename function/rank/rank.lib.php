<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function page_range($all, $row, $now, $div)
{
    $all = intval($all);
    $row = intval($row);
    $now = intval($now);
    $div = intval($div);

    $maxR = 1 + intval(($all - 1) / $row);

    if ($now < 1) {
        $now = 1;
    }
    if ($now > $maxR) {
        $now = $maxR;
    }

    $L = max(1, $now - $div);
    $d = $div - ($now - $L);
    $R = min($maxR, $now + $d + $div);

    return [$L, $now, $R];
}

function getCBdatabyid($id)
{
    $tbstats = DB::tname('statsboard');
    if (is_numeric($id)) {
        $id = (string) $id;
    }
    if (!is_string($id)) {
        return false;
    }
    if (!preg_match('/^[0-9]+$/', $id)) {
        return false;
    }
    if ($res = DB::query("SELECT * FROM `$tbstats` WHERE `id` = $id")) {
        return DB::fetch($res);
    }

    return false;
}

function PrepareBoardData($cbdata, $build = false)
{
    global $_E;
    $id = $cbdata['id'];

    $boarddata = null;
    $buildtime = 0;
    //Get Data
    if (!$build && $cache = DB::loadcache("cache_board_$id")) {
        //cached!

        $boarddata = $cache['data'];
        $buildtime = $cache['time'];
        if ($cache['time'] < time()) {
            $_E['template']['cbrebuild'] = true;
        }
    } else {
        if ($build) {
            $userlist = extend_userlist($cbdata['userlist']);
            if (!$userlist) {
                return false;
            }
            $boarddata = buildcbboard($id, $userlist);
            $boarddata = sortboard($boarddata);
        } else {
            //only make a empty board

            $boarddata = buildcbboard($id, []);
            $_E['template']['cbrebuild'] = true;
        }

        if ($boarddata) {
            $time = time();
            $buildtime = $time;
            DB::putcache("cache_board_$id",
                        ['data' => $boarddata, 'time' => $time + 900],
                        'forever');
        } else {
            return false;
        }
    }

    nickname($boarddata['userlist']);
    $_E['template']['plist'] = $boarddata['probinfo'];
    $_E['template']['user'] = $boarddata['userlist'];
    $_E['template']['userdetail'] = $boarddata['userdetail'];
    $_E['template']['buildtime'] = date('Y-m-d H:i:s', $buildtime);
    $_E['template']['owner'] = -1;
    foreach ($boarddata['userlist'] as $uid) {
        foreach ($boarddata['problems'] as $pname) {
            $_E['template']['s'][$uid][$pname]['vid'] = verdictIDtoword($boarddata['ratemap'][$uid][$pname]);
            $_E['template']['s'][$uid][$pname]['challink'] = $boarddata['challink'][$uid][$pname];
        }
    }

    return $boarddata;
}

function checkpostdata($array)
{
    $res = [];
    foreach ($array as $p) {
        if (!isset($_POST[$p])) {
            return false;
        }
        $res[$p] = $_POST[$p];
    }

    return $res;
}

define('verdictIDlist', serialize([0, 10, 15, 20, 30, 35, 40, 45, 50, 60, 70, 80, 90]));
function verdictIDtoword($vid)
{
    $res = 'NO';
    switch ($vid) {
        case 10: $res = 'SE'; break; //Submission error
        case 15: $res = 'NJ'; break; //Can't be judged
        case 20: $res = 'IQ'; break; //In queue
        case 30: $res = 'CE'; break; //Compile error
        case 35: $res = 'RF'; break; //Restricted function
        case 40: $res = 'RE'; break; //Runtime error
        case 45: $res = 'OE'; break; //Output limit
        case 50: $res = 'TLE'; break; //Time limit
        case 60: $res = 'MLE'; break; //Memory limit
        case 70: $res = 'WA'; break; //Wrong answer
        case 80: $res = 'PE'; break; //PresentationE
        case 90: $res = 'AC'; break; //Accepted
    }

    return $res;
}

function ranksort_calc_ac(&$list, $bd)
{
    if (!function_exists('ranksort_calc_ac_cmp')) {
        function ranksort_calc_ac_cmp($a, $b)
        {
            global $bd;
            if ($bd[$a][90] == $bd[$b][90]) {
                return 0;
            }

            return ($bd[$a][90] < $bd[$b][90]) ? -1 : 1;
        }
    }
    usort($list, 'ranksort_calc_ac_cmp');
}

function buildcbboard($bid, $selectuser = null)
{
    static $class = null;
    static $vidlist = null;
    if ($class === null) {
        $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
        $vidlist = unserialize(verdictIDlist);
    }
    $res = [];
    //check bid
    if (!is_numeric($bid)) {
        return false;
    }
    $bid = intval($bid);
    if ($bid <= 0) {
        return false;
    }

    $boarddata = getCBdatabyid($bid);
    if (!$boarddata) {
        return false;
    }

    $res['name'] = $boarddata['name'];
    $res['owner'] = $boarddata['owner'];
    $res['createtime'] = $boarddata['timestamp'];

    $res['userlist'] = extend_userlist($boarddata['userlist']);
    $res['problems'] = extend_problems($boarddata['problems']);

    if ($res['userlist'] === false) {
        $res['userlist'] = [];
    }
    if ($res['problems'] === false) {
        $res['problems'] = [];
    }

    //delete not avaible uid
    $useracct_sql = DB::getuserdata('account', $res['userlist'], 'uid');
    $res['userlist'] = [];
    foreach ($useracct_sql as $uid => $data) {
        if ($selectuser === null || in_array($uid, $selectuser)) {
            $res['userlist'][] = $uid;
        }
    }

    //get oj account
    $userojacct = [];
    foreach ($res['userlist'] as $uid) {
        $tmp = new UserInfo($uid);
        $userojacct[$uid] = $tmp->load_data('ojacct');
    }

    //setproblem
    $problist_sorted = [];
    $probleminfo = [];
    //分類
    foreach ($res['problems'] as $pname) {
        $probdata['name'] = $pname;
        $probdata['show'] = $pname;
        $probdata['oj'] = '';
        foreach ($class as $classname => $c) {
            if (preg_match($c->pattern, $pname)) {
                $probdata['oj'] = $classname;
                $problist_sorted[$classname][] = $pname;
                break;
            }
        }
        $probleminfo[$pname] = $probdata;
    }

    //預處理
    foreach ($problist_sorted as $classname => $arr) {
        if (method_exists($class[$classname], 'preprocess')) {
            $class_acct = [];
            foreach ($userojacct as $acct) {
                if ($acct[$classname] && $class[$classname]->checkid($acct[$classname]['account'])) {
                    $class_acct[] = $acct[$classname]['account'];
                }
            }
            if (!empty($class_acct)) {
                $class[$classname]->preprocess($class_acct, $arr);
            }
        }
        if (method_exists($class[$classname], 'showname')) {
            foreach ($arr as $pn) {
                $probleminfo[$pn]['show'] = $class[$classname]->showname($pn);
            }
        }
    }

    //set problem data and user acct to res
    //$res['userojacct'] = $userojacct;
    $res['probinfo'] = $probleminfo;
    $res['ratemap'] = [];
    $res['challink'] = [];
    if (!($accache = DB::loadcache("rate_ac_cb_$bid"))) {
        $accache = [];
    }
    //ratemap
    foreach ($userojacct as $uid => $u) {
        foreach ($probleminfo as $pname => $p) {
            //Find in AC CACHE
            if (array_key_exists($uid, $accache) && isset($accache[$uid][$pname])) {
                $re = 90;
            }
            //Match a judge & account
            elseif ($p['oj'] && $u[$p['oj']]['account']) {
                $re = $class[$p['oj']]->query($u[$p['oj']]['account'], $pname);
            }
            //Unavailable
            else {
                $re = 0;
            }
            //Set to Ratemap
            $res['ratemap'][$uid][$pname] = $re;

            //if AC, Save to cache
            if ($re == 90) {
                $accache[$uid][$pname] = 1;
            }

            //challink
            $res['challink'][$uid][$pname] = '';
            if ($p['oj'] && method_exists($class[$p['oj']], 'challink') && $re) {
                $res['challink'][$uid][$pname] = $class[$p['oj']]->challink($u[$p['oj']]['account'], $pname);
            }
        }
    }

    //sort user, may be it need perfect structure
    $userdetail = [];
    $sortrule = 'ranksort_calc_ac';
    $emptystatistics = [];
    $res['userdetail'] = [];
    foreach ($vidlist as $vid) {
        $emptystatistics[$vid] = 0;
    }
    foreach ($res['userlist'] as $uid) {
        $info = [];
        $info['oj'] = $userojacct[$uid];
        $info['score'] = 0;
        $info['statistics'] = $emptystatistics;
        foreach ($res['ratemap'][$uid] as $value) {
            $info['statistics'][$value]++;
            //if set score rule . it should add here!
        }
        $res['userdetail'][$uid] = $info;
    }
    DB::putcache("rate_ac_cb_$bid", $accache, 'forever');

    return $res;
}

function merge_cb_rate_map($direct, $data)
{
    foreach ($data['ratemap'] as $uid => $arr) {
        if (!isset($direct['ratemap'][$uid])) {
            $direct['ratemap'][$uid] = [];
        }
        $direct['ratemap'][$uid] = array_merge($direct['ratemap'][$uid], $arr);
    }

    foreach ($data['challink'] as $uid => $arr) {
        if (!isset($direct['challink'][$uid])) {
            $direct['challink'][$uid] = [];
        }
        $direct['challink'][$uid] = array_merge($direct['challink'][$uid], $arr);
    }

    foreach ($data['userlist'] as $uid) {
        if (!in_array($uid, $direct['userlist'])) {
            $direct['userlist'][] = $uid;
        }
    }

    foreach ($data['userdetail'] as $uid => $data) {
        $direct['userdetail'][$uid] = $data;
    }
    sort($direct['userlist']);

    return $direct;
}
function sortboard($board, $sortrule = 'sort_by_ac')
{
    $u = $board['userlist'];
    $usernum = count($u);
    for ($i = 0; $i < $usernum; $i++) {
        for ($j = $i + 1; $j < $usernum; $j++) {
            switch ($sortrule) {
                case 'sort_by_ac':
                    if ($board['userdetail'][$u[$i]]['statistics']['90'] <
                        $board['userdetail'][$u[$j]]['statistics']['90']) {
                        $t = $u[$i];
                        $u[$i] = $u[$j];
                        $u[$j] = $t;
                    }
                    break;
                case 'sort_by_id_desc':
                    if ($u[$i] < $u[$j]) {
                        $t = $u[$i];
                        $u[$i] = $u[$j];
                        $u[$j] = $t;
                    }
                    break;
            }
        }
    }
    $board['userlist'] = $u;

    return $board;
}
class UniversalScoreboard
{
    private $id;
    private $table;
    private $userlist_etd;
    private $problems_etd;

    public function __construct($id)
    {
        $tusboard = DB::tname('stats');
        if (is_numeric($id)) {
            if ($id != 0) {
                $res = getCBdatabyid($id);
                if ($res) {
                    $this->id = $id;
                    $this->table = $res;
                    $this->userlist_etd = extend_userlist($res['userlist']);
                    $this->problems_etd = extend_problems($res['problems']);
                } else {
                    $this->id = -1;
                }
            } else {
                $this->id = 0;
                $this->userlist_etd = [];
                $this->problems_etd = [];
                $this->table = [
                    'name'      => '',
                    'owner'     => '',
                    'timestamp' => DB::timestamp(),
                    'userlist'  => '',
                    'problems'  => '',
                    'announce'  => '',
                    'state'     => 1,
                ];
            }
        } else {
            $this->id = -1;
        }
    }

    public function isload()
    {
        return $this->id >= 0;
    }

    public function userlist()
    {
        return $this->userlist;
    }

    public function problems()
    {
        return $this->problems;
    }

    public function ori_userlist()
    {
        return $this->table['userlist'];
    }

    public function ori_problems()
    {
        return $this->table['problems'];
    }

    public function id()
    {
        return $this->id;
    }

    public function load_data()
    {
        return $this->table;
    }

    public function save_data($table, &$errmsg = null)
    {
        try {
            $tusboard = DB::tname('statsboard');
            if (empty($table['name']) || !is_string($table['name'])) {
                throw new Exception('name error');
            } else {
                $table['name'] = DB::real_escape_string($table['name']);
            }
            if (!is_numeric($table['owner'])) {
                throw new Exception('owner error');
            }
            if (!($users = extend_userlist($table['userlist']))) {
                throw new Exception('userlist error:'.$table['userlist']);
            }
            if (!($probs = extend_problems($table['problems']))) {
                throw new Exception('problems error');
            }
            $table['announce'] = DB::real_escape_string($table['announce']);
            if (!is_numeric($table['state'])) {
                throw new Exception('state error');
            }

            if ($this->id == 0) {
                $cid = 'NULL';
            } else {
                $cid = $this->id;
            }
            $cname = $table['name'];
            $cowner = $table['owner'];
            $cul = DB::real_escape_string($table['userlist']);
            $cps = DB::real_escape_string($table['problems']);
            $cannounce = $table['announce'];
            $cts = $table['state'];

            $res = DB::query("INSERT INTO `$tusboard`
                (`id`, `name`, `owner`, `timestamp`, `userlist`, `problems`, `announce` ,`state`) VALUES
                ($cid,'$cname',$cowner,CURRENT_TIMESTAMP,'$cul','$cps','$cannounce',$cts)
                ON DUPLICATE KEY UPDATE
                `name` = '$cname',
                `userlist` = '$cul',
                `problems` = '$cps',
                `announce` = '$cannounce' ");
            if (!$res) {
                throw new Exception('SQL error');
            }
            if ($cid === 'NULL') {
                $table['id'] = $this->id = DB::insert_id();
            }
            $this->userlist_etd = $users;
            $this->problems_etd = $probs;
            $this->table = $res;
        } catch (Exception $e) {
            if (isset($errmsg)) {
                $errmsg = $e->getMessage();
            }

            return false;
        }

        return true;
    }
}
