<?php namespace SkyOJ\File;
/*
base    /cont //put problem decript
        /assert //put static assert
        /testdata/data/
        /testdata/make/
        /testdata/checker/
        judge.json //judge setting

*/
class ProblemDataManager extends ManagerBase
{
    #predefine some file name
    const PROBLEM_JSON_FILE = 'prob.json';
    const CONT_DIR = 'cont/';
    const CONT_ROW_FILE = 'cont/cont.row';
    const CONT_HTML_FILE = 'cont/cont.html';
    const CONT_PDF_FILE = 'cont/cont.pdf';
    const ATTACH_DIR = 'attach/';
    const TESTDATA_DIR = 'testdata/data/';
    const FILENAME_PATTEN = '/^[a-zA-Z0-9\.]{1,64}$/';

    const INPUT_EXT = ["txt","in"];
    const OUTPUT_EXT = ["ans","out"];
    private $pid;
    public function __construct(int $id,bool $builddir = false)
    {
        $this->pid = $id;
        $this->subrootname = 'problem/'.Path::id2folder($id);
        if( !file_exists($this->base()) )
        {
            if( !$builddir )
                throw new ProblemDataManagerException('No Such Problem!');
            if( !$this->buildStructure() )
                throw new ProblemDataManagerException('buildStructure fail');
        }
    }

    public function buildStructure():bool
    {
        $res = true;
        $res &= $this->mkdir('cont');
        $res &= $this->mkdir('attach');
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

    public function getAttachFiles():array
    {
        return glob($this->base().self::ATTACH_DIR.'*');
    }

    public function getTestdataFiles():array
    {
        return glob($this->base().self::TESTDATA_DIR.'*');
    }

    public function copyTestcasesZip(string $filepath,bool $cover = true):array
    {
        if( !class_exists('\\ZipArchive') )
            throw new ProblemDataManagerException('php ZipArchive not enabled!');
        $zip = new \ZipArchive;
  
        if( $zip->open($filepath) === false )
            throw new ProblemDataManagerException('Not a zip file!');

        $tmpdir = tempnam( sys_get_temp_dir() , 'CAS' );

        if( file_exists($tmpdir) )
            unlink($tmpdir);
        mkdir($tmpdir);
 
        $zip->extractTo($tmpdir);
        $zip->close();
        $files = glob($tmpdir.'/*');

        foreach($files as $file)
        {
            $info = pathinfo($file);
            if( in_array($info['extension'],self::INPUT_EXT) || in_array($info['extension'],self::OUTPUT_EXT) )
            {
                $this->copyin($file,self::TESTDATA_DIR.$info['basename']);
            }
        }

        return [];
    }
}

class ProblemDataManagerException extends \Exception {} 