<?php namespace SkyOJ\Scoreboard;

class ScoreBoardTypeEnum extends \SkyOJ\Helper\Enum
{
    const ScoreBoard = 1;
}

class ScoreBoardAllowJoinEnum extends \SkyOJ\Helper\Enum
{
    const NotAllowed = 0;
    const Allowed = 1;
    //const NeedJudge = 2;
}
