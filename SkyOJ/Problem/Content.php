<?php namespace SkyOJ\Problem;

use \SkyOJ\File\ProblemDataManager;

abstract class Content
{
    public static function init(int $type, ProblemDataManager $manager):?Content
    {
        if( !ContentTypenEnum::isValidValue($type) )
            return null;
        $classname = __NAMESPACE__.'\\'.ContentTypenEnum::str($type);
        return new $classname($manager);
    }

    abstract public function __construct(ProblemDataManager $manager);
    abstract public function getRowContent();
    abstract public function getRendedContent();
    
    abstract public function setContent(string $data);
    abstract public function praseRowContent():bool;
    
}