<?php namespace SkyOJ\Problem;

use \SkyOJ\File\ProblemManager;

class ContentTypenEnum extends \SkyOJ\Helper\Enum
{
    const MarkdownContent  = 1;
}

abstract class Content
{
    public static function init(int $type, ProblemManager $manager):?Content
    {
        if( !ContentTypenEnum::isValidValue($type) )
            return null;
        $classname = __NAMESPACE__.'\\'.ContentTypenEnum::str($type);
        return new $classname($manager);
    }

    abstract public function __construct(ProblemManager $manager);
    abstract public function getRowContent();
    abstract public function getRendedContent();
    
    abstract public function setContent(string $data);
    abstract public function praseRowContent():bool;
    
}