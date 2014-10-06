<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
userControl::DelLoginToken();
header("Location:index.php");
