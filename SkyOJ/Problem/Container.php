<?php namespace SkyOJ\Problem;
/*
base    /cont //put problem decript
        /assert //put static assert
        /testdata/data/
        /testdata/make/
        /testdata/checker/
        judge.json //judge setting

*/
use \SkyOJ\File\ProblemManager;

class Container extends \SkyOJ\Core\CommonObject implements \SkyOJ\Core\Permission\Permissible
{
    protected static $table = 'problem'; 
    protected static $prime_key = 'pid';
    private $ProblemManager;
    private $content_type;
    private $json;

    function __construct()
    {

    }

    static public function create(int $owner):int
    {
        $default = [
            'owner' => $owner,
            'content_access' => ProblemLevel::Hidden,
            'submit_access'  => ProblemLevel::Hidden,
            'codeview_access'=> ProblemLevel::Hidden,
            'title' => '[Empty Problem]',
            'judge' => '',
            'judge_type' => 0,
            'content_type' => ProblemDescriptionEnum::MarkDown
        ];
        return self::insertInto($default);
    }

    protected function afterLoad()
    {
        $this->ProblemManager = new ProblemManager($this->pid,true);
        $this->json = json_decode($this->ProblemManager->read(ProblemManager::PROBLEM_JSON_FILE),true);
        $this->content_type = $this->json['content']['type'];
        return true;
    }

    public function isSubmitFuncOpen()
    {
        return $this->submit_access == ProblemSubmitLevel::Open;
    }

    public function isAllowSubmit($User)
    {
        if( !$User->checkPermission($this) )
            return false;
        if( !$User->isUser() )
            return false;
        return true;
    }

    public function isAllowEdit($User)
    {
        if( !$User->checkPermission($this) )
            return false;
        if( !$User->isAdmin() )
            return false;
        return true;
    }

    public function getObjLevel():int
    {
        return $this->content_access;
    }

    private function praseRowContent():bool
    {
        switch($this->content_type)
        {
            case ProblemDescriptionEnum::MarkDown:
                $Parsedown = new \Parsedown();
                $val = $this->ProblemManager->read(ProblemManager::CONT_ROW_FILE);
                $val = $Parsedown->text($val);
                $this->ProblemManager->write(ProblemManager::CONT_HTML_FILE,$val);
                break;
            case ProblemDescriptionEnum::HTML:
                $val = $this->ProblemManager->read(ProblemManager::CONT_ROW_FILE);
                $this->ProblemManager->write(ProblemManager::CONT_HTML_FILE,$val);
                break;
            default:
                //Nothing To do
        }
        return true;
    }

    public function setRowContent(string $data,int $format):bool
    {
        switch($format)
        {
            case ProblemDescriptionEnum::MarkDown:
            case ProblemDescriptionEnum::HTML:
                $this->ProblemManager->write(ProblemManager::CONT_ROW_FILE,$data);
                break;
            case ProblemDescriptionEnum::PDF:
                $this->ProblemManager->move(ProblemManager::CONT_PDF_FILE,$data);
                break;
            default:
                throw new ContainerException('NO SUCH format!');
        }
        $this->content_type = $format;
        return $this->praseRowContent();
    }

    public function getContentType()
    {
        return $this->content_type;
    }

    public function getRendedContent()
    {
        switch($this->content_type)
        {
            case ProblemDescriptionEnum::MarkDown:
            case ProblemDescriptionEnum::HTML:
                return $this->ProblemManager->read(ProblemManager::CONT_HTML_FILE);
            case ProblemDescriptionEnum::PDF:
                return null;
        }
    }

    public function getRowContent():string
    {
        switch($this->content_type)
        {
            case ProblemDescriptionEnum::MarkDown:
            case ProblemDescriptionEnum::HTML:
                return $this->ProblemManager->read(ProblemManager::CONT_ROW_FILE);
            case ProblemDescriptionEnum::PDF:
                return '';
        }
    }

    public function getFileManager()
    {
        return $this->ProblemManager;
    }

    public function genAttachLocalPath(string $file)
    {
        if( !$this->ProblemManager->checkFilename($file) )
            throw new ContainerException('FILENAME NOT AVAILABLE');
        return $this->ProblemManager->base().ProblemManager::ATTACH_DIR.$file;
    }

    public function getTestdata():array
    {
        $files = $this->ProblemManager->getTestdataFiles();
        $map = [];
        foreach($files as $file)
        {
            $info = pathinfo($file);
            if( !isset($map[$info['filename']]) )
                $map[$info['filename']] = [];
            if( in_array($info['extension'],ProblemManager::INPUT_EXT) )
                $map[$info['filename']][0] = $file;
            else if( in_array($info['extension'],ProblemManager::OUTPUT_EXT) )
                $map[$info['filename']][1] = $file;
        }

        $res = [];
        foreach($map as $io)
        {
            if(!empty($io))
            {
                $res[] = new Testdata($io[0]??NULL,$io[1]??NULL);
            }
        }
        return $res;
    }

    public function checkSet_title(string $string):bool
    {
        if( !is_string($string) )
            throw new ContainerModifyException('type fail');
        if( strlen($string) > 200 )
            throw new ContainerModifyException('title len limit error');
        return true;
    }

    public function checkSet_judge(string $class):bool
    {
        if( $class != $this->judge && $class != '' )
        {
            //TODO : Check for installed
            if( !\Plugin::isClassName($class) )
                throw new ContainerModifyException('no such judge');
        }
        return true;
    }

    public function checkSet_content_access($val):bool
    {
        if( ProblemLevel::isValidValue($val) )
            throw new ContainerModifyException('no such ContentAccess type');
        return true;
    }

    public function checkSet_submit_access($val):bool
    {
        if( ProblemSubmitLevel::isValidValue($val) )
            throw new ContainerModifyException('no such ProblemSubmitLevel type');
        return true;
    }

    public function checkSet_codeview_access($val):bool
    {
        //if( ProblemLevel::isValidValue($val) )
        //    throw new ContainerModifyException('no such ContentAccess type');
        return true;
    }

    public function checkSet_admmsg($val):bool
    {
        return true;
    }
}

class ContainerException extends \Exception { }
class ContainerModifyException extends \Exception { }
