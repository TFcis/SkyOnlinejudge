<?php namespace SkyOJ\Score\Plugin;

/*
[[S1,D1],[S2,D2]]
S1 is Group1's score
D1 is how many testcase has in Group1
S2 is Group2's score
D2 is how many testcase has in Group2
If you have 5 testcase, and score type is [[20,2],[50,2],[30,1]]
Testcase 1,2 is the first group, and the group's score is 20
Testcase 3,4 is the second group, and the group's score is 50
Testcase 5 is the third group, and the group's score is 30
Total score is 100
Every group's score is decided by the min result in it's all testcase
If the judge use diff
    AC result = 1
    WA result = 0
*/
class GroupMin extends \SkyOJ\Score\ScoreMode
{
    const VERSION = '1.0';
    const NAME = 'GroupMin';
    const DESCRIPTION = 'GroupMin score type from CMS';
    const COPYRIGHT = 'lys0829';

    public static function patten():string
    {
        return "[[S1,D1],[S2,D2]]";
    }
    public static function is_match(string $scoretype):bool
    {
        return json_decode($scoretype);
    }
    public static function calculate(string $scoretype,$res)
    {
        $scoretype = json_decode($scoretype);
        $scoregroup = [];
        $id = 0;

        if( $scoretype === null )
            throw new \Exception("Json Error!");

        foreach($scoretype as $row)
        {
            $g = [];
            $g['score'] = $row[0];
            $g['minid'] = $id;
            $id+=$row[1];
            $g['maxid'] = $id-1;
            $g['score_total'] = 0.0;
            $g['used'] = false;
            $scoregroup[$id] = $g;
        }
        $score = 0.0;
        foreach($res->tasks as $t)
        {
            //echo $t->id."\n";
            foreach($scoregroup as $id => $sc)
            {
                if($t->id>=$sc['minid'] && $t->id<=$sc['maxid'])
                {
                    //echo $t->id." ".$t->score." ".$sc['score']."\n";
                    if(!$scoregroup[$id]['used'])
                    {
                        $scoregroup[$id]['score_total'] = $t->score*$sc['score'];
                        $scoregroup[$id]['used'] = true;
                    }
                    else
                    {
                        $scoregroup[$id]['score_total'] = min($scoregroup[$id]['score_total'],$t->score*$sc['score']);
                    }
                    break;
                }
            }
        }
        //echo var_dump($scoregroup)."\n";
        foreach($scoregroup as $sc)
        {
            $score += $sc['score_total'];
        }
        return $score;
    }
}