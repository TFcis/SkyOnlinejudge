<?php namespace SkyOJ\Problem;

use \SkyOJ\File\ProblemManager;

class PDFContent extends Content
{
    private $m_problem_manager;
    public function __construct(ProblemManager $manager)
    {
        $this->m_problem_manager = $manager;
    }

    public function getRowContent():string
    {
        return $this->m_problem_manager->read(ProblemManager::CONT_ROW_FILE);
    }

    public function getRendedContent():string
    {
        return $this->m_problem_manager->read(ProblemManager::CONT_HTML_FILE);
    }

    public function setContent(string $data)
    {
        $this->m_problem_manager->write(ProblemManager::CONT_ROW_FILE,$data);
    }

    public function praseRowContent():bool
    {
        $val = $this->m_problem_manager->read(ProblemManager::CONT_ROW_FILE);
		$pdfFile = $this->m_problem_manager->base().ProblemManager::ATTACH_DIR.$val;
		if( !file_exists($pdfFile) )
		{
			return false;
		}
		$PDFRender = new \SkyOJ\Render\Component\PDFRender($val);
        $val = $PDFRender->html();
        $this->m_problem_manager->write(ProblemManager::CONT_HTML_FILE,$val);
        return true;
    }
}