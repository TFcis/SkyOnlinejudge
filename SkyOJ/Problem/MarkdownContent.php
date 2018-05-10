<?php namespace SkyOJ\Problem;

use \SkyOJ\File\ProblemDataManager;

class MarkdownContent extends Content
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
        $Parsedown = new \Parsedown();
        $val = $this->m_problem_data_manager->read(ProblemDataManager::CONT_ROW_FILE);
        $val = $Parsedown->text($val);
        $this->m_problem_data_manager->write(ProblemDataManager::CONT_HTML_FILE,$val);
        return true;
    }
}