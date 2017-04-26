<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$tmpl['title']}.csv");
header("Pragma: no-cache");
header("Expires: 0");

echo $tmpl['csv_string'];
?>
