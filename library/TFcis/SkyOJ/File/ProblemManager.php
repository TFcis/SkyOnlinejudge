<?php namespace SkyOJ\File;
/*
base    /cont //put problem decript
        /assert //put static assert
        /testdata/data/
        /testdata/make/
        /testdata/checker/
        judge.json //judge setting

*/
class ProblemManager extends ManagerBase
{
    const CONT_DIR = 'cont';
    private $pid;
    public function __construct(int $id)
    {
        $this->pid = $id;
        $this->subrootname = 'problem/'.self::id2folder($id).self::DIR_SPILT_CHAR;
        if( !file_exists($this->subrootname) )
        {
            if( !$this->buildStructure() )
                throw new ProblemManagerException('buildStructure fail');
        }
    }

    public function buildStructure():bool
    {
        $res = true;
        $res &= $this->mkdir('cont');
        $res &= $this->mkdir('assert');
        $res &= $this->mkdir('testdata/data');
        $res &= $this->mkdir('testdata/make');
        $res &= $this->mkdir('testdata/checker');
        return $res;
    }
}

class ProblemManagerException extends \Exception {}