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

class Container extends \SkyOJ\Core\CommonObject
{
    protected $table = 'problem'; 
    protected $prime_key = 'pid';
    private $pid;
    private $ProblemManager;
    private $content_type;
    private $json;

    function __construct(?int $pid)
    {
        if( !is_null($pid) )
        {
            if( !$this->Load($pid) )
                throw new ContainerException('NO SUCH PROBLEM'); 
        }
    }

    protected function afterLoad()
    {
        $pid = (int)$this->sqldata['pid'];
        $this->ProblemManager = new ProblemManager($pid);
        $this->json = json_decode($this->ProblemManager->read(ProblemManager::PROBLEM_JSON_FILE),true);
        $this->content_type = $this->json['content']['type'];
        $this->pid = $pid;
        return true;
    }

    private function praseRowContent():bool
    {
        switch($this->content_type)
        {
            case ProblemDescriptionEnum::MarkDown:
                $Parsedown = new \parsedown\Parsedown();
                $val = $this->ProblemManager->read(ProblemManager::CONT_ROW_FILE);
                $val = $Parsedown->text($val);
                $this->ProblemManager->write($val,ProblemManager::CONT_HTML_FILE);
            case ProblemDescriptionEnum::HTML:
                $val = $this->ProblemManager->read(ProblemManager::CONT_ROW_FILE);
                $this->ProblemManager->write($val,ProblemManager::CONT_HTML_FILE);
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
                $this->ProblemManager->write($data,ProblemManager::CONT_ROW_FILE);
            case ProblemDescriptionEnum::PDF:
                $this->ProblemManager->move($data,ProblemManager::CONT_PDF_FILE);
            default:
                throw new ContainerException('NO SUCH format!');
        }
        $this->content_type = $format;
        return $this->praseRowContent();
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

    public function genAssertLocalPath(string $file)
    {
        if( !$this->ProblemManager->checkFilename($file) )
            throw new ContainerException('FILENAME NOT AVAILABLE');
        return $this->ProblemManager->base().ProblemManager::ASSERT_DIR.$file;
    }
}

class ContainerException extends \Exception { }