<?php
$isCLI = (php_sapi_name() == 'cli');
if (!$isCLI) {
    die('Please run me from the console - not from a web-browser!');
}

$base = __DIR__.'/../';
chdir($base);
echo "RUN ROOT AT ",getcwd(),PHP_EOL;

require_once 'vendor/autoload.php';
require 'config/config.php';
require 'GlobalSetting.php';
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

        echo "SET Submit(any>0) TO Open",PHP_EOL;
        DB::queryEx("UPDATE `{$t}` SET `submit_access`=? WHERE `submit_access`>0",SkyOJ\Problem\ProblemSubmitLevel::Open);


        echo PHP_EOL,"===CHANGE PROBLEM CONTENT LEVEL!===",PHP_EOL,PHP_EOL;

        echo "SET HIDDEN(0) TO Hidden",PHP_EOL;
        DB::queryEx("UPDATE `{$t}` SET `content_type`=? WHERE `content_type` = 0", \SkyOJ\Problem\ContentTypenEnum::MarkdownContent);
    }

	function moveProblemData()
	{
        global $_E;

		$t = DB::tname('problem');
        $pids = DB::fetchAllEx("SELECT `pid` FROM {$t} WHERE 1");
        \SkyOJ\File\Path::initialize($_E['DATADIR']);
        
        echo PHP_EOL,"===UPDATE PROBLEM FORMAT!===",PHP_EOL,PHP_EOL;

        foreach($pids as $row)
        {
            $pid = $row['pid'];
            try {
                $row = @file_get_contents("./data/problem/$pid/http/row.txt");
                $json = @file_get_contents("./data/problem/$pid/$pid.json");
                
                #Create new problem dir
                $manger = new \SkyOJ\File\ProblemDataManager($pid, true);
 
                $problem = new \SkyOJ\Problem\Container;
                if( !$problem->load($pid) )
                    throw new Exception("Can not oprn problem $pid");

                $problem->setContent($row, (int)\SkyOJ\Problem\ContentTypenEnum::MarkdownContent);
                $problem->setJudgeJson($json);

                //try fix setting
                if( $setting = json_decode($json, true) )
                {
                    $problem->memory_limit  = (int)$setting['memlimit']??1024*1024;
                    $problem->runtime_limit = $setting['timelimit']??1000;
                }
                $problem->save();
            } catch(Exception $e) {
                echo "Error at problem ",$pid,":",$e->getMessage(),PHP_EOL;
            }
        }
	}

    function run()
    {
        global $base;
        self::$base = $base;
        $this->connect();
        $this->fixUserLevel();
        $this->fixProblem();
		$this->moveProblemData();
    }
}

$app = new NewProblemUpdate();
$app->run();

echo "DONE. Please check your SKYOJ System",PHP_EOL;
