<?php
$isCLI = ( php_sapi_name() == 'cli' );
if(!$isCLI)
{
    die("Please run me from the console - not from a web-browser!");
}
require_once('../config/config.php');

function run($str)
{
    if(!mysql_query($str))
    {
        exit(mysql_error()."\n".$str);
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
//CREATE TABLE 
$conn = mysql_connect($_config['db']['dbhost'],$_config['db']['dbuser'],$_config['db']['dbpw']);

if(!$conn){
    die('ERROR:'.mysql_error());
}
echo "MySQL Connect!\n";
mysql_query("SET NAMES 'utf8'");
mysql_select_db($_config['db']['dbname']);

run("CREATE TABLE IF NOT EXISTS `".tname('account')."` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `passhash` varchar(200) COLLATE utf8_bin NOT NULL,
  `nickname` varchar(64) COLLATE utf8_bin NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");

run("CREATE TABLE IF NOT EXISTS `".tname('usertoken')."` (
  `uid` int(11) NOT NULL,
  `timeout` int(11) NOT NULL,
  `type` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

run("CREATE TABLE IF NOT EXISTS `".tname('cache')."` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `timeout` int(11) NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

run("CREATE TABLE IF NOT EXISTS `".tname('ojlist')."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` text COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `available` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;")
?>