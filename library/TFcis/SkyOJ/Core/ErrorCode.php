<?php namespace SkyOJ\Core;

class ErrorCode extends \SkyOJ\Helper\Enum
{
    const ERROR_NO = 0;

    const NO_SUCH_METHOD = 1;
    const NO_SUCH_ENUM_VALUE = 2;
    const NO_SUCH_DATA = 3;
    const UNKNOWN_ERROR = 9999;
}