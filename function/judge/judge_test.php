<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

$challenge = new challenge(1, 1, 'test', 'cpp');
$judge = new judge($challenge);
$judge->start();
