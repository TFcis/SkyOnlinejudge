<?php namespace SkyOJ\Score\Plugin;

class Average extends \SkyOJ\Score\ScoreMode
{
    const VERSION = '1.0';
    const NAME = 'Average';
    const DESCRIPTION = 'Average score type from CMS';
    const COPYRIGHT = 'LFsWang';

    public static function patten():string
    {
        return "[[S1,D1],[S2,D2]]";
    }
    public static function is_match(string $scoretype):bool
    {
        return json_decode($scoretype);
    }
    public static function calculate(string $scoretype, $res)
    {
        $ac  = 0;
        $all = 0;

        $max = 100;

        foreach($res->tasks as $row)
        {
            $all++;
            if(  $row->result_code == 20 )
                $ac++;
        }

        if( $all == 0 )  $all = 1;

        return $max * ( $ac / $all );
    }
}