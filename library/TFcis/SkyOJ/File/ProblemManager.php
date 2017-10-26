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
    const CONT_DIR = 'cont/';
    const CONT_ROW_FILE = 'cont/conf.row';
    const CONT_HTML_FILE = 'cont/conf.html';
    const CONT_PDF_FILE = 'cont/conf.pdf';

    private $pid;
    public function __construct(int $id)
    {
        $this->pid = $id;
        $this->subrootname = 'problem/'.Path::id2folder($id);
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