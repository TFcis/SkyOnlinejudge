<?php namespace SkyOJ\Problem;

use \SkyOJ\File\ProblemManager;

class MarkdownContent extends Content
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
        $Parsedown = new \Parsedown();
        $val = $this->m_problem_manager->read(ProblemManager::CONT_ROW_FILE);
        $val = $Parsedown->text($val);
        $this->m_problem_manager->write(ProblemManager::CONT_HTML_FILE,$val);
        return true;
    }
}