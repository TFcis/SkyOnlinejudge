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
/*
if(!mysql_query("CREATE TABLE ".$_config['db']['tablepre']."account ".
                                ""
))
{
    echo(mysql_error()."\n");
}
else
    echo("SUCC\n");
*/
?>