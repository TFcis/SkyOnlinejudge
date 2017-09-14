<?php namespace SkyOJ\Plugin;

abstract class Scoreboard
{
    abstract function patten():string;
    abstract function is_match(string $name):bool;

    //problem information
    abstract function get_title(string $name):?string;

}