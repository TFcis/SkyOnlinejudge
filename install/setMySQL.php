<?php
$isCLI = ( php_sapi_name() == 'cli' );
if(!$isCLI)
{
    die("Please run me from the console - not from a web-browser!");
}
require_once('../config/config.php');

function run($str,$abort = true)
{
    if(!mysql_query($str))
    {
        if($abort)
            exit(mysql_error()."\n".$str);
        else
            echo mysql_error()."\n".$str."\n";
    }
    else
    {
        echo("succ\n");
    }
}
function tname($table)
{
    global $_config;
    return $_config['db']['tablepre']."_$table";
}
$skysystem = tname('skysystem');
$account   = tname('account');
$usertoken = tname('usertoken');
$cache     = tname('cache');
$ojlist    = tname('ojlist');
$statsboard= tname('statsboard');
$syslog    = tname('syslog');
$userojacct= tname('userojacct');
//CREATE TABLE 
$conn = mysql_connect($_config['db']['dbhost'],$_config['db']['dbuser'],$_config['db']['dbpw']);
if(!$conn){
    die('ERROR:'.mysql_error());
}
echo "MySQL Connect!\n";
mysql_query("SET NAMES 'utf8'");
mysql_select_db($_config['db']['dbname']);

//get version

run("CREATE TABLE IF NOT EXISTS `$skysystem` (
  `name` char(64) COLLATE utf8_bin NOT NULL,
  `var` text COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

$res = mysql_query("SELECT `var` FROM `$skysystem` WHERE `name` LIKE 'sqlversion'");
if( $res &&  $data = mysql_fetch_array($res) )
{
    $var = intval($data['var']);
}
else
{
    echo mysql_error()."\n";
    $var = 0;
}

echo "Your Version : $var \n";
switch($var)
{
case 0:
run("CREATE TABLE IF NOT EXISTS `$account` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `passhash` varchar(200) COLLATE utf8_bin NOT NULL,
  `nickname` varchar(64) COLLATE utf8_bin NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");

run("CREATE TABLE IF NOT EXISTS `$usertoken` (
  `uid` int(11) NOT NULL,
  `timeout` int(11) NOT NULL,
  `type` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

run("CREATE TABLE IF NOT EXISTS `$cache` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `timeout` int(11) NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

run("CREATE TABLE IF NOT EXISTS `$ojlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` char(64) COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `available` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");


/*run("CREATE TABLE IF NOT EXISTS `".tname('userojlist')."` (
  `uid` int(11) NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
");*/

run("CREATE TABLE IF NOT EXISTS `".tname('plugin')."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` char(64) COLLATE utf8_bin NOT NULL,
  `version` text COLLATE utf8_bin NOT NULL,
  `author` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class` (`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");

run("CREATE TABLE IF NOT EXISTS `$statsboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `owner` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userlist` text COLLATE utf8_bin NOT NULL,
  `problems` text COLLATE utf8_bin NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");

case 1:
    run("ALTER TABLE  `$ojlist` 
    CHANGE  `class`  `class` CHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ;");
    run("ALTER TABLE  `$ojlist` ADD UNIQUE (`class`);");
    run("ALTER TABLE  `$account` ADD UNIQUE (`nickname`);");
//nothing
case 2:
    run("ALTER TABLE  `$statsboard` ADD  `announce` TEXT NULL AFTER  `problems` ;",false);
case 3:
    run("CREATE TABLE IF NOT EXISTS `$syslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `namespace` char(64) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");

    run("CREATE TABLE IF NOT EXISTS `$userojacct` (
  `indexid` char(40) COLLATE utf8_bin NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `account` char(64) COLLATE utf8_bin NOT NULL,
  `approve` int(11) NOT NULL,
  PRIMARY KEY (`indexid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
    run("DROP TABLE  ".tname('userojlist'),false);
}

    


$version = 3;
run("INSERT INTO `$skysystem`
    (`name`, `var`) VALUES
    ('sqlversion',$version)
    ON DUPLICATE KEY UPDATE `var` = $version");