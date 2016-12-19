<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//TODO Common Crash Page
function GetPasswordHash(string $password)
{
    $re = password_hash($password, PASSWORD_BCRYPT);
    if ($re === false) {
        \LOG::msg(\Level::Critical, 'passwordHash Fail!', $password);
        //Crash!
    }

    return $re;
}

define('NOE_FAIL', 0);
define('NOE_IS_NICKNAMEL', 1);
define('NOE_IS_EMAI', 2);

function CheckPasswordFormat(string $password)
{
    $pattern = '/^[._@a-zA-Z0-9]{3,30}$/';

    return preg_match($pattern, $password);
}

function CheckEmailFormat(string $email)
{
    $pattern = '/^[A-z0-9_.]{1,30}@[A-z0-9_.]{1,20}$/';

    return preg_match($pattern, $email);
}

function checknickname($name)
{
    \LOG::msg(\Level::Notice, 'checknickname() is Old Function!');
    if ($name !== addslashes($name)) {
        return false;
    }
    if (strpos($name, '@') !== false) {
        return false;
    }

    return true;
}

function CheckUidFormat($uid):bool
{
    if( !is_string($uid) && !is_integer($uid) )
        return false;
    return preg_match('/^([1-9]{1}[0-9]*$|0)$/',$uid);
}

//function register
//return : array( bool res , mixed des )
// res : True Register Successful
//        des = empty string
//     : False
//        des = Error Information
//TODO : Use Common Error Id To Replace Const-Strings
function register(string $email, string $nickname, string $password, string $repeat)
{
    $acctable = \DB::tname('account');
    $resultdata = [false, ''];

    if (!CheckEmailFormat($email) || !CheckPasswordFormat($password) || $password != $repeat ||
        !checknickname($nickname)) {
        //use language!
        $resultdata[1] = '格式錯誤';

        return $resultdata;
    }

    //$nickname = addslashes($nickname);
    $password = GetPasswordHash($password);
    if (!\DB::queryEx("INSERT INTO `$acctable` ".
                    '(`uid`, `email`, `passhash`, `nickname`, `timestamp`) '.
                    'VALUES (NULL,?,?,?,CURRENT_TIMESTAMP)', $email, $password, $nickname)) {
        $resultdata[1] = '帳號已被註冊';

        return $resultdata;
    }
    $resultdata[0] = true;

    return $resultdata;
}

//function login
//return : array( bool res , mixed des )
// res : True Login Successful
//        des = $userdata
//     : False
//        des = Error Information
//TODO : Use Common Error Id To Replace Const-Strings
function login(string $userinput, string $password)
{
    global $_E;

    $_E['template']['login'] = [];
    $acctable = \DB::tname('account');
    $sqlres;
    $userdata = null;
    $resultdata = [false, ''];

    $email = $userinput;
    if (!CheckEmailFormat($email)) {
        $res = \DB::fetchEx("SELECT `email` FROM `$acctable` WHERE `nickname`=?", $userinput);
        if ($res === false) {
            $resultdata[1] = '暱稱錯誤';

            return $resultdata;
        }
        $email = $res['email'];
    }

    if (!CheckPasswordFormat($password)) {
        $resultdata[1] = '帳密錯誤';

        return $resultdata;
    }

    $userdata = \DB::fetch("SELECT * FROM  `$acctable`".
                        'WHERE  `email` = ?', [$email]);
    if ($userdata === false) {
        $resultdata[1] = '無此帳號';

        return $resultdata;
    }
    if (!password_verify($password, $userdata['passhash'])) {
        $resultdata[1] = '密碼錯誤';

        return $resultdata;
    }
    $resultdata[0] = true;
    $resultdata[1] = $userdata;

    return $resultdata;
}

function page_ojacct($uid)
{
    global $_E;
    $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
    $table_oj = \DB::tname('ojlist');
    $_E['template']['oj'] = [];

    if (!isset($_E['ojlist'])) {
        if (!envadd('ojlist')) {
            return false;
        }
    }

    $userdata = new UserInfo($uid);
    $userojacctlist = $userdata->load_data('ojacct');

    foreach ($_E['ojlist'] as $oj) {
        $tmp = $oj;
        $tmp['info'] = '';
        $tmp['user'] = $userojacctlist[$oj['class']];
        $tmp['c'] = $class[$oj['class']];
        if ($tmp['user']['account']) {
            if ($tmp['user']['approve'] == 0) {
                // No Check

                $tmp['info'] = '尚未認證';
            } else {
                if (method_exists($class[$oj['class']], 'account_detail')) {
                    $tmp['info'] = $class[$oj['class']]->account_detail($tmp['user']['account']);
                    if (!$tmp['info']) {
                        $tmp['info'] = '';
                    }
                    $tmp['info'] = '已驗證 '.$tmp['info'];
                }
            }
        }
        $_E['template']['oj'][] = $tmp;
    }

    return true;
}

function modify_ojacct($argv, $euid)
{
    global $_E;
    $table = \DB::tname('userojlist');
    if (!isset($_E['ojlist'])) {
        envadd('ojlist');
    }
    $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');

    $userdata = new UserInfo($euid);
    $uacct = $userdata->load_data('ojacct');

    foreach ($argv as $oj => $acct) {
        if (!empty($acct)) {
            if ($uacct[$oj]['approve'] == 0 && $class[$oj]->checkid($acct)) {
                $uacct[$oj]['account'] = $acct;
                $uacct[$oj]['approve'] = 0;
            } else {
                return [false, "Accout error :$oj"];
            }
        }
    }
    if ($userdata->save_data('ojacct', $uacct)) {
        return [true];
    }

    return [false, 'SQL ERROR'];
}

function getgravatarlink($email, $size = null)
{
    if (!is_string($email) || !is_numeric($size) && $size !== null) {
        return '';
    }
    $email = md5(strtolower(trim($email)));
    $res = "//www.gravatar.com/avatar/$email?";

    //check
    $check = $res.'d=404';
    $header = get_headers('http:'.$check);
    if ($header[0] == 'HTTP/1.0 404 Not Found') {
        $res = "//www.gravatar.com/avatar/$email?d=identicon&";
    }

    if (isset($size)) {
        $res .= "?s=$size";
    }

    return $res;
}

class UserInfo
{
    private $uid;
    private $data;

    public static function GetUserData(string $table, $uids, string $qdata = '*')
    {
        $table = \DB::tname($table);
        $resdata = [];

        if (empty($uids)) {
            return [];
        }

        if (!is_array($uids)) {
            $uids = [$uids];
        }

        $q = \DB::genQuestListSign(count($uids));
        //$uids =  implode(',', array_map('intval', $uids) );

        $res = \DB::fetchAll("SELECT $qdata FROM `$table` WHERE `uid` IN($q)", $uids);
        if ($res === false) {
            return false;
        }

        foreach ($res as $r) {
            $resdata[$r['uid']] = $r;
        }
        //\LOG::msg(\Level::Debug, 'GetUserData', $resdata);

        return $resdata;
    }

    public function __construct(int $uid = 0, bool $debug = false)
    {
        $acceptflag = true;

        //guest
        if ($uid == 0) {
            $acceptflag = false;
        }
        //registed user
        $acctdata = self::GetUserData('account', $uid);
        //\LOG::msg(\Level::Debug,"acctdata",$uid);
        if ($acctdata === false || !isset($acctdata[$uid])) {
            $acceptflag = false;
        }

        if ($acceptflag) {
            $this->uid = $uid;
            $this->data['account'] = $acctdata[$uid];
        } else {
            $this->data['account'] = null;
            if ($uid === 0) {
                $this->uid = 0;
            } else {
                $this->uid = -1;
            }
        }
    }

    public function uid()
    {
        return $this->uid;
    }
    
    public function is_registed()
    {
        return $this->uid > 0;
    }

    public function is_guest()
    {
        return $this->uid === 0;
    }

    public function is_load()
    {
        return $this->uid !== -1;
    }

    private function _load_data($name)
    {
        $method = "_load_data_$name";
        if (method_exists(get_class(), $method)) {
            if ($data = $this->$method()) {
                return $this->data[$name] = $data;
            }
        }

        return false;
    }

    private function _save_data_account($data, $cg = null)
    {
        $taccount = \DB::tname('account');
        $info = \DB::ArrayToQueryString($data);
        \Log::msg(\Level::Debug, "UPDATE `{$taccount}` SET {$info['update']} WHERE `uid`={$this->uid}", $info);
        if (!\DB::query("UPDATE {$taccount} SET {$info['update']} WHERE `uid`={$this->uid}", $info['data'])) {
            return false;
        }

        return true;
    }

    private function _load_data_view()
    {
        $res = self::GetUserData('profile', $this->uid);
        if (isset($res[$this->uid])) {
            $res = $res[$this->uid];
        } else {
            $res = [];
            $p = rand(1, 5);
            switch ($p) {
                case 1:
                    $res['quote'] = 'The value of a man resides in what he gives and not in what he is capable of receiving.';
                    $res['quote_ref'] = 'Albert Einstein';
                    break;
                case 2:
                    $res['quote'] = 'In the End, we will remember not the words of our enemies, but the silence of our friends.';
                    $res['quote_ref'] = 'Martin Luther King, Jr.';
                    break;
                case 3:
                    $res['quote'] = 'If you shed tears when you miss the sun, you also miss the stars.';
                    $res['quote_ref'] = 'Robíndronath Thakur';
                    break;
                case 4:
                    $res['quote'] = 'Histories make men wise ; poems witty; the mathematics subtle; natural philosophy deep ; moral grave ; logic and rhetoric able to contend.';
                    $res['quote_ref'] = 'Francis Bacon';
                    break;
                default:
                    $res['quote'] = 'A man provided with paper, pencil, and rubber, and subject to strict discipline, is in effect a universal Turing Machine.';
                    $res['quote_ref'] = 'Alan Mathison Turing';
                    break;
            }
            $res['avaterurl'] = '';
            $res['backgroundurl'] = '//i.imgur.com/n2EOWhO.jpg';
            $this->_save_data_view($res);
        }
        $res['quote'] = htmlspecialchars($res['quote']);
        $res['quote_ref'] = htmlspecialchars($res['quote_ref']);
        $res['nickname'] = $this->data['account']['nickname'];

        return $res;
    }

    private function _save_data_view($viewdata, $cg = null)
    {
        $tprofile = \DB::tname('profile');
        if (!isset($viewdata['quote'])) {
            $viewdata['quote'] = '';
        }
        if (!isset($viewdata['quote_ref'])) {
            $viewdata['quote_ref'] = '';
        }
        if (!isset($viewdata['backgroundurl'])) {
            $viewdata['backgroundurl'] = '';
        }
        if (!isset($viewdata['avatarurl'])) {
            $viewdata['avatarurl'] = '';
        }

        $quote = $viewdata['quote'];
        $quote_ref = $viewdata['quote_ref'];
        $backgroundurl = $viewdata['backgroundurl'];
        $avatarurl = $viewdata['avatarurl'];

        $uid = $this->uid;
        $res = \DB::queryEx("INSERT INTO `$tprofile` (`uid`, `quote`, `quote_ref`, `avatarurl`, `backgroundurl`)
                                    VALUES (?,?,?,?,?)
                                    ON DUPLICATE KEY
                                    UPDATE  `quote` = ?,
                                            `quote_ref` = ?,
                                            `avatarurl`=?,
                                            `backgroundurl`=?", $uid, $quote, $quote_ref, $avatarurl, $backgroundurl,
                                                  $quote, $quote_ref, $avatarurl, $backgroundurl);
        if ($res === false) {
            throw new Exception('error');
        }

        return true;
    }

    private function _load_data_ojacct()
    {
        $userojaccttable = \DB::tname('userojacct');
        $res = \DB::query("SELECT * FROM `$userojaccttable` WHERE `uid` = ".$this->uid);
        if (!$res) {
            return false;
        }
        $val = [];
        while ($tmp = \DB::fetch($res)) {
            $val[] = $tmp;
        }
        $flag = false;
        $res = ojacct_reg($val, $this->uid, $flag);
        if ($flag) {
            $this->_save_data_ojacct($res);
        }
        Render::errormessage($flag);

        return $res;
    }

    private function _save_data_ojacct($ojarray, $cg = null)
    {
        $userojaccttable = \DB::tname('userojacct');
        //remove old data
        if (isset($cg)) {
            if (is_array($cg[0])) {
                \DB::syslog('RM'.$cg[0], 'ojacct');
                foreach ($cg[0] as $indexid) {
                    \DB::query("DELETE FROM `$userojaccttable` WHERE `indexid` = '$indexid'");
                }
            }
            if (is_array($cg[1])) {
                foreach ($ojarray as $row) {
                    if (in_array($row['indexid'], $cg[1])) {
                        \DB::syslog('ADD'.$row['indexid'], 'ojacct');
                        $uid = (int) $row['uid'];
                        $id = (int) $row['id'];
                        $indexid = "$uid+$id";
                        $account = \DB::real_escape_string($row['account']);
                        $approve = (int) $row['approve'];
                        \DB::query("INSERT INTO `$userojaccttable` (`indexid`,`uid`,`id` ,`account`,`approve`)
                                    VALUES ('$indexid',  $uid,  $id,  '$account',  $approve)
                                    ON DUPLICATE KEY
                                    UPDATE `account` = '$account' , `approve` = $approve");
                    }
                }
            }
        } else {
            \DB::syslog('GLOBAL ADD'.$this->uid, 'ojacct');
            \DB::query("DELETE FROM `$userojaccttable` WHERE `uid` = ".$this->uid);
            foreach ($ojarray as $row) {
                $uid = (int) $row['uid'];
                $id = (int) $row['id'];
                $indexid = "$uid+$id";
                $account = \DB::real_escape_string($row['account']);
                $approve = (int) $row['approve'];
                \DB::query("INSERT INTO `$userojaccttable` (`indexid`,`uid`,`id` ,`account`,`approve`)
                            VALUES ('$indexid',  $uid,  $id,  '$account',  '$approve')");
            }
        }

        return true;
    }

    public function load_data($dataname)
    {
        $res = null;
        //account is available when class constructed
        $available_argvs = ['account', 'view', 'ojacct'];

        if (!in_array($dataname, $available_argvs)) {
            return;
        }

        if (!isset($this->data[$dataname])) {
            return $this->_load_data($dataname);
        }

        return $this->data[$dataname];
    }

    public function save_data($dataname, $value, $args = null)
    {
        $available_argvs = ['account', 'ojacct', 'view'];

        if (!in_array($dataname, $available_argvs)) {
            return false;
        }
        try {
            $method = "_save_data_$dataname";
            if (method_exists(get_class(), $method)) {
                $this->$method($value, $args);
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function account($s)
    {
        return $this->data['account'][$s];
    }
}
