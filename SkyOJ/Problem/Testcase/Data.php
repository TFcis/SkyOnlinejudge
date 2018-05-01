<?php namespace SkyOJ\Problem\Testcase;

class Data
{
    private $m_id;
    private $m_input;
    private $m_output;
    private $m_runtime_limit;
    private $m_memory_limit;

    function __construct(int $id, string $in, string $out,int $tle ,int $mle)
    {
        $this->m_id     = $id;
        $this->m_input  = $in;
        $this->m_output = $out;
        $this->m_runtime_limit  = $tle;
        $this->m_memory_limit   = $mle;
    }

    public function id()
    {
        return $this->m_id;
    }

    public function input(bool $fullname = false)
    {
        return $fullname ? $this->m_input : basename($this->m_input);
    }

    public function output(bool $fullname = false)
    {
        return $fullname ? $this->m_output : basename($this->m_output);
    }

    public function runtime_limit()
    {
        return $this->m_runtimelimit;
    }

    public function memory_limit()
    {
        return $this->m_memory_limit;
    }
}