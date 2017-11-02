<?php
$isCLI = (php_sapi_name() == 'cli');
if (!$isCLI) {
    die('Please run me from the console - not from a web-browser!');
}

$base = __DIR__.'/../';
echo "RUN ROOT AT $base",PHP_EOL;

require_once $base.'vendor/autoload.php';
require $base.'config/config.php';
use \SkyOJ\Core\Database\DB as DB;

class NewProblemUpdate{
    private static $base;
    private function connect()
    {
        global $_config;
        $db = $_config['db'];
        DB::$prefix = $db['tablepre'];
        DB::initialize($db['query_string'], $db['dbuser'], $db['dbpassword'],$db['dbname']);
        DB::query('SET NAMES UTF8');
    }

    function fixUserLevel()
    {
        include self::$base."LocalSetting.php";
        $roots = $_E['site']['admin']??[1];
        echo "SET THOSE TO ROOT USERS :",implode(',',$roots),PHP_EOL;
        $t = DB::tname('account');
        DB::queryEx("UPDATE `{$t}` SET `level`=? WHERE 1",SkyOJ\Core\Permission\UserLevel::USER);
        foreach( $roots as $uid )
            DB::queryEx("UPDATE `{$t}` SET `level`=? WHERE `uid`=?",SkyOJ\Core\Permission\UserLevel::ROOT,$uid);
    }

    function fixProblem()
    {
        $t = DB::tname('problem');
        echo PHP_EOL,"===CHANGE PROBLEM ACCESS LEVEL!===",PHP_EOL,PHP_EOL;

        echo "SET HIDDEN(0) TO Hidden",PHP_EOL;
        DB::queryEx("UPDATE `{$t}` SET `content_access`=? WHERE `content_access`=0",SkyOJ\Problem\ProblemLevel::Hidden);

        echo "SET Open(1) TO Open",PHP_EOL;
        DB::queryEx("UPDATE `{$t}` SET `content_access`=? WHERE `content_access`=1",SkyOJ\Problem\ProblemLevel::Open);

        echo "SET Contest(2) TO ADMIN",PHP_EOL;
        DB::queryEx("UPDATE `{$t}` SET `content_access`=? WHERE `content_access`=2",SkyOJ\Problem\ProblemLevel::Admin);
    }

    function run()
    {
        global $base;
        self::$base = $base;
        $this->connect();
        $this->fixUserLevel();
        $this->fixProblem();
    }
}

$app = new NewProblemUpdate();
$app->run();
