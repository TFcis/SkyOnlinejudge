<?php namespace SkyOJ\Problem;

class ProblemSubmitLevel extends \SkyOJ\Helper\Enum
{
    const Closed    = 0; //< All users cannot submit (it will not effct submited challenge)
    const Open      = 1;
}
