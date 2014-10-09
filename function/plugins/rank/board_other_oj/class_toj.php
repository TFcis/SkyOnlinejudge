<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class class_toj{
    public $version = '1.0';
    public $name = 'Toj capturer';
	public $description = 'TOJ capturer for test';
	public $copyright = 'test by LFsWang';
	public $pattern = "/^toj_[0-9]+$/";
	function __construct()
	{
	    echo 'Toj capturer create!';
	}
}