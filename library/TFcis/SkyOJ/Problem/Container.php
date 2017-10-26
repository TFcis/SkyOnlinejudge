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

class Container
{
    private $pid;
    private $ProblemManager;
    private $content_type;

    function __construct(int $pid)
    {
        $ProblemManager = new ProblemManager($pid);
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
}

class ContainerException extends \Exception { }