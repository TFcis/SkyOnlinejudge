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
    #predefine some file name
    const PROBLEM_JSON_FILE = 'prob.json';
    const CONT_DIR = 'cont/';
    const CONT_ROW_FILE = 'cont/cont.row';
    const CONT_HTML_FILE = 'cont/cont.html';
    const CONT_PDF_FILE = 'cont/cont.pdf';
    const ASSERT_DIR = 'assert/';
    const FILENAME_PATTEN = '/^[a-zA-Z0-9\.]{1,64}$/';

    private $pid;
    public function __construct(int $id,bool $builddir = false)
    {
        $this->pid = $id;
        $this->subrootname = 'problem/'.Path::id2folder($id);
        if( !file_exists($this->base()) )
        {
            if( !$builddir )
                throw new ProblemManagerException('No Such Problem!');
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

    public function checkFilename($name):bool
    {
        if( !is_string($name) ) return false;
        return preg_match(self::FILENAME_PATTEN,$name);
    }
}

class ProblemManagerException extends \Exception {}