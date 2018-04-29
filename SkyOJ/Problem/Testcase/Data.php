<?php namespace SkyOJ\Problem\Testcase;

class Data
{
    private $m_id;
    private $m_input;
    private $m_output;

    function __construct(int $id,string $in,string $out)
    {
        $this->m_id     = $id;
        $this->m_input  = $in;
        $this->m_output = $out;
    }

    public function id()
    {
        return $this->id;
    }

    public function input()
    {
        return basename($this->m_input);
    }

    public function output()
    {
        return basename($this->m_output);
    }
}