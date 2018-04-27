<?php namespace SkyOJ\Challenge;

class ResultCode extends \SkyOJ\Helper\Enum
{
    const WAIT      = 0;
    const JUDGING   = 10;
    const AC        = 20;
    const PE        = 25;
    const WA        = 30;
    const OLE       = 35;
    const RE        = 40;
    const TLE       = 50;
    const MLE       = 60;
    const RF        = 65;
    const CE        = 70;
    const JE        = 80;
}