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
        $data->chal_id = (int) $challenge->get_id();
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
        LOG::msg(Level::Debug, '', $data);

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
            //put result
            $judge_result = json_decode($judge_result);
            $result->verdict = $judge_result->verdict;
            $result->chal_id = $judge_result->chal_id;
            $result->result = $judge_result->result;
            $result->uid = $this->challenge->get_uid();
        }
        //處理state與計分
        $data = $this->score($this->pjson->score, $result->result);
        $result->state = $data['state'];
        $result->score = $data['score'];
        $this->putdata($result);
    }

    private function putdata($result)
    {
        global $_E;
        $tchal = DB::tname('challenge');
        $tuser = DB::tname('account');
        $sql = "UPDATE `$tchal` SET `score`=? , `result`=?  WHERE `id`=?";
        $sql_user_score = "UPDATE `$tchal` SET `score`=? WHERE `id`=?";
        $data = [];
        $data['score'] = $result->score;
        $data['state'] = $result->state;
        $data['chal_id'] = $result->chal_id;
        $data['uid'] = $result->uid;
        DB::query($sql, [$data['score'], $data['state'], $data['chal_id']]);
        DB::query($sql_user_score, [$data['score'], $data['uid']]);
        file_create($_E['challenge']['path'].'result/'.$this->challenge->get_id().'.json', json_encode($result->result));
    }

    private function score($type, $result)
    {
        switch($type)
        {
            case "rate": return $this->score_rate($result);
            //case "special": return ;
        }
    }

    private function score_rate($result)
    {
        $state = 1;
        $score = 0;
        foreach ($result as $resultdata) {
            if ($resultdata->state == 1) {
                $score += $this->pjson->test[$resultdata->test_idx]->weight;
            } else {
                if ($resultdata->state > $state) {
                    $state = $resultdata->state;
                }
            }
        }
        $data['score'] = $score;
        $data['state'] = $state;
        return $data;
    }

    //private function score_special($result)
}
