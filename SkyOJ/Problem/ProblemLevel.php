<?php namespace SkyOJ\Problem;

class ProblemLevel extends \SkyOJ\Helper\Enum
{
    const Hidden    = \SkyOJ\Core\Permission\ObjectLevel::ADMIN_PRIVATE; //< Only Access >= can see problem
    const Admin     = \SkyOJ\Core\Permission\ObjectLevel::ADMIN;
    const Open      = \SkyOJ\Core\Permission\ObjectLevel::EVERYONE; //< All users
}
