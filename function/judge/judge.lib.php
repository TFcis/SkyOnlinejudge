<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once 'judge_json.php';

class judge
{
    private $post;
    private $challenge;
    private $pjson;

    public function __construct($challenge)
    {
        global $_config;
        $this->challenge = $challenge;
        $this->post = new post($_config['connect']['judgehost']);
        $this->pjson = $this->get_pjson($challenge->get_pid());
        LOG::msg(Level::Debug, '', $this->pjson->test[0]->data);
    }

    private function get_pjson($pid)
    {
        global $_E;
        $path = $_E['problem']['path'].$pid.'/conf.json';
        $data = file_read($path);
        if ($data === false) {
            LOG::msg(Level::Warning, "cannot get problem's conf.json ,pid=$pid");

            return false;
        } else {
            $data = json_decode($data);

            return $data;
        }
    }

    private function make_json()
    {
        global $_E;
        $challenge = $this->challenge;
        $data = new json_main();
        $data->chal_id = (int)$challenge->get_id();
        //$data->chal_id = 573;
        /*$data['code_path'] = $_E['challenge']['path'].'code/'.$challenge->get_id().'.'.$challenge->get_suffix();
        $data['res_path'] = $_E['problem']['path'].$challenge->get_pid().'/res/';*/
        $data->code_path = 'tests/testdata/test.cpp';
        $data->res_path = 'tests';
        if (!$this->pjson) {
            LOG::msg(Level::Warning, 'make_json error');

            return false;
        } else {
            $data->comp_type = $this->pjson->compile;
            $data->check_type = $this->pjson->check;
            $time = $this->pjson->timelimit;
            $mem = $this->pjson->memlimit;
            $i = 0;
            foreach ($this->pjson->test as $testdata) {
                $test = new json_test();
                $test->test_idx = $i;
                $test->timelimit = $time;
                $test->memlimit = $mem;
                $j = 0;
				$test->metadata = new json_testdata();
                foreach ($testdata->data as $id) {
                    $test->metadata->data[$j] = $id;
                    $j++;
                }
                $data->test[$i] = $test;

                $i++;
            }
        }
        $data->metadata = new json_chalmeta();
		LOG::msg(Level::Debug, '',$data);

        return json_encode($data);
    }

    public function start()
    {
        $judgeconnect = $this->post;
        if (!$json = $this->make_json()) {
            LOG::msg(Level::Warning, 'because make json error,cannot judge');

            return false;
        }
        LOG::msg(Level::Debug, 'judge now!!!');
        $judge_result = $judgeconnect->send($json); //judge
        $this->getresult($judge_result);
    }

    private function getresult($judge_result)
    {
        $result = new json_result();
        if (!$judge_result) {
            return false;
        } else {
            $judge_result = json_decode($judge_result);
            $result->verdict = $judge_result->verdict;
            $result->chal_id = $judge_result->chal_id;
            $result->result = $judge_result->result;
        }
        $ac = 1;
        foreach ($result->result as $resultdata) {
            if ($resultdata->state == 1) {
                $result->score = $this->pjson->test[$resultdata->test_idx]->weight;
            } else {
                if ($resultdata->state > $ac) {
                    $ac = $resultdata->state;
                }
            }
        }
        $result->state = $ac;
        $this->putdata($result);
    }

    private function putdata($result)
    {
        global $_E;
        $tchal = DB::tname('challenge');
        $sql = "UPDATE `$tchal` SET `score`=? , `result`=?  WHERE `id`=?";
        $data = [];
        $data['score'] = $result->score;
        $data['state'] = $result->state;
        $data['chal_id'] = $result->chal_id;
        DB::query($sql, [$data['score'], $data['state'], $data['chal_id']]);
        file_create($_E['challenge']['path'].'result/'.$this->challenge->get_id().'.json', json_encode($result->result));
    }
}
