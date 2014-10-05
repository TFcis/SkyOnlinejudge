<?php
$isCLI = ( php_sapi_name() == 'cli' );
if(!$isCLI)
{
    die("Please run me from the console - not from a web-browser!");
}
require_once('../config/config.php');
//CREATE TABLE 
$conn = mysql_connect($_config['db']['dbhost'],$_config['db']['dbuser'],$_config['db']['dbpw']);

if(!$conn){
    die('ERROR:'.mysql_error());
}
echo "Connect!\n";
mysql_query("SET NAMES 'utf8'");
mysql_select_db($_config['db']['dbname']);

if(!mysql_query("CREATE TABLE IF NOT EXISTS `tojtest_account` (".
    "`uid` int(11) NOT NULL AUTO_INCREMENT,".
    "`email` varchar(64) NOT NULL,".
    "`passhash` varchar(200) NOT NULL,".
    "`nickname` varchar(64) NOT NULL,".
    "`timestamp` datetime NOT NULL,".
    "PRIMARY KEY (`uid`),".
    "KEY `uid` (`uid`)".
    ") ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;"))
{
    echo(mysql_error()."\n");
}
else
    echo("SUCC\n");

?>