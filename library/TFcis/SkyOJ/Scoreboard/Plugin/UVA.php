<?php namespace SkyOJ\Scoreboard\Plugin;

class UVA extends \SkyOJ\Plugin\Scoreboard
{
    function patten():string
    {
        return "/^uva[0-9]+$/i";
    }
    function is_match(string $name):bool
    {
        return preg_match($this->patten(),$name)===1;
    }

    function get_title(string $name):?string
    {
        return $name;
    }

    private function matched_name_to_pid($name)
    {
        return substr($name,3);
    }
    
}