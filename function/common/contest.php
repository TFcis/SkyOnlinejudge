<?php namespace SKYOJ;

if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
/**
 * @file contest.php
 * @brief Contest System Interface
 *
 * @author LFsWang
 * @copyright 2016 Sky Online Judge Project
 */

class ContestUserRegisterStateEnum extends BasicEnum
{
    const NotAllow  = 0; //< Only admin allow add user to contest
    const Open      = 1; //< All user without guest can join contest within time limit
    const PermitRequired = 2; //< require admin check
}

class ContestTeamStateEnum extends BasicEnum
{
    const Pending    = 0; //< Wait for admin permit
    const Accept     = 1; //< Normal Team
    const Hidden     = 2; //< Unlist Hidden Team for test contest
    const Reject     = 3; //< Reject
    const Unofficial = 4; //< list but not get award on scoreboard
    const Virtual    = 10;//< Join via virtual contest system
    const Dropped    = 99;//< may be some guy use hack!?

    function allow(int $Case):bool
    {
        switch($Case)
        {
            case self::Accept:
            case self::Hidden:
            case self::Unofficial:
            case self::Virtual:
                return true;
            default:
                return false;
        }
    }
}