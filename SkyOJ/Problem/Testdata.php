<?php namespace SkyOJ\Problem;

class Testdata
{
    public $input;
    public $output;

    function __construct($in,$out)
    {
        $this->input = $in;
        $this->output = $out;
    }

    public function input()
    {
        return basename($this->input);
    }

    public function output()
    {
        return basename($this->output);
    }
}
