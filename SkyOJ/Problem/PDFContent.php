<?php namespace SkyOJ\Problem;

use \SkyOJ\File\ProblemDataManager;

class PDFContent extends Content
{
    private $m_problem_data_manager;
    public function __construct(ProblemDataManager $manager)
    {
        $this->m_problem_data_manager = $manager;
    }

    public function getRowContent():string
    {
        return $this->m_problem_data_manager->read(ProblemDataManager::CONT_ROW_FILE);
    }

    public function getRendedContent():string
    {
        return $this->m_problem_data_manager->read(ProblemDataManager::CONT_HTML_FILE);
    }

    public function setContent(string $data)
    {
        $this->m_problem_data_manager->write(ProblemDataManager::CONT_ROW_FILE,$data);
    }

    public function praseRowContent():bool
    {
        $val = $this->m_problem_data_manager->read(ProblemDataManager::CONT_ROW_FILE);
		$pdfFile = $this->m_problem_data_manager->base().ProblemDataManager::ATTACH_DIR.$val;
		if( !file_exists($pdfFile) )
		{
			return false;
		}
		$PDFRender = new \SkyOJ\Render\Component\PDFRender($val);
        $val = $PDFRender->html();
        $this->m_problem_data_manager->write(ProblemDataManager::CONT_HTML_FILE,$val);
        return true;
    }
}