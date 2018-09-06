<?php namespace SkyOJ\Challenge;

use \SkyOJ\Core\User\User;

class Container extends \SkyOJ\Core\CommonObject implements \SkyOJ\Core\Permission\Permissible
{
    protected static $table = 'challenge'; 
    protected static $prime_key = 'cid';

    private static $m_problems = [];

    private $m_problem;

    public static function create(int $owner, string $code, int $pid, string $lang, int $compiler):int
    {
        $default = [
            'pid'  => $pid,
            'uid'  => $owner,
            'code' => $code,
            'comment'  => '',
            'language' => $lang,
            'compiler' => $compiler,
            'result'   => ResultCode::WAIT,
            'runtime'  => 0,
            'memory'   => 0,
            'score'    => 0,
            'package'  => ''
        ];
        return self::insertInto($default);
    }

    private function ensureProblemExist(int $pid)
    {
        if( !isset(self::$m_problems[$pid]) )
        {
            self::$m_problems[$pid] = new \SkyOJ\Problem\Container();
            if( !self::$m_problems[$pid]->load($pid) )
                throw new ContainerException('Load Problem Error');
        }
        $this->m_problem =& self::$m_problems[$pid];
    }

    protected function afterLoad()
    {
        $this->ensureProblemExist($this->pid);
        return true;
    }

    public function readable(User $user):bool
    {
        //TODO
        return true;
    }

    public function writeable(User $user):bool
    {
        return $user->isAdmin();
    }

    public static function creatable(User $user):bool
    {
        return $this->m_problem->isAllowSubmit($user);
    }

    public function &problem()
    {
        return $this->m_problem;
    }

    public function applyResult($res)
    {
        $runtime = 0;
        $result = 0;
        $ac = 0;
        $all = count($res->tasks)??1;
        foreach($res->tasks as $row)
        {
            $runtime += $row->runtime;
            @$this->memory = $this->memory + $row->memory;
            $result= max($result, $row->result_code);
            if( $row->result_code == 20 )
                $ac++;
        }
        @$this->score = (int)($ac/$all*100);
        @$this->runtime = $runtime;
        @$this->package = json_encode($res);
        @$this->result = $result;
        $this->save();
    }
}

class ContainerException extends \Exception { }