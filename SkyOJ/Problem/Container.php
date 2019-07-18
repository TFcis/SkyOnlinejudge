<?php namespace SkyOJ\Problem;
/*
base    /cont //put problem decript
        /assert //put static assert
        /testdata/data/
        /testdata/make/
        /testdata/checker/
        judge.json //judge setting

*/
use \SkyOJ\Core\User\User;
use \SkyOJ\Core\Permission\ObjectLevel;
use \SkyOJ\File\ProblemDataManager;
use \SkyOJ\Judge\JudgeProfileEnum;
use \SkyOJ\Score;

class Container extends \SkyOJ\Core\CommonObject implements \SkyOJ\Core\Permission\Permissible
{
    protected static $table = 'problem'; 
    protected static $prime_key = 'pid';
    private $m_problem_data_manager;
    private $json;
    private $m_content;

    function __construct()
    {

    }

    static public function create(int $owner):int
    {
        $default = [
            'owner' => $owner,
            'content_access' => ProblemLevel::Hidden,
            'submit_access'  => ProblemLevel::Hidden,
            'codeview_access'=> ProblemLevel::Hidden,
            'title' => '[Empty Problem]',
            'judge_profile' => JudgeProfileEnum::None,
            'content_type' => ProblemDescriptionEnum::MarkDown,
            'memory_limit' => 1048576, //1 Mb
            'runtime_limit' => 1000,   //1 Second
        ];
        return self::insertInto($default);
    }

    protected function afterLoad()
    {
        $this->m_problem_data_manager = new ProblemDataManager($this->pid, true);
        $this->m_content = Content::init($this->content_type, $this->m_problem_data_manager);
        $this->json = json_decode($this->m_problem_data_manager->read(ProblemDataManager::PROBLEM_JSON_FILE),true);
        return true;
    }

    public function isSubmitFuncOpen()
    {
        return $this->submit_access == ProblemSubmitLevel::Open;
    }

    public function readable(User $user):bool
    {
        return $user->testStisfyPermission($this->owner, $this->content_access);
    }

    public function writeable(User $user):bool
    {
        return $user->testStisfyPermission($this->owner, ObjectLevel::atLeastAdmin($this->content_access));
    }

    public static function creatable(User $user):bool
    {
        return $user->isAdmin();
    }


    public function isAllowSubmit(User $user)
    {
        //return $this->readable($user) && $this->isSubmitFuncOpen();
        //TODO
        return true;
    }

    public function getObjLevel():int
    {
        return $this->content_access;
    }

    public function getRowContent():string
    {
        return $this->m_content->getRowContent();
    }

    public function getRendedContent():string
    {
        return $this->m_content->getRendedContent();
    }

    public function setContent(string $data,int $format):bool
    {
        if( $this->content_type !== $format )
        {
            $this->content_type = $format;
            $this->m_content = Content::init($this->content_type, $this->m_problem_data_manager);
        }
        $this->m_content->setContent($data);
        return $this->m_content->praseRowContent();
    }

    private function praseRowContent():bool
    {
        return $this->m_content->praseRowContent();
    }

    public function getJudgeJson()
    {
        return $this->m_problem_data_manager->read('judge.json');
    }

    public function getDataManager()
    {
        return $this->m_problem_data_manager;
    }

    public function genAttachLocalPath(string $file)
    {
        if( !$this->m_problem_data_manager->checkFilename($file) )
            throw new ContainerException('FILENAME NOT AVAILABLE');
        return $this->m_problem_data_manager->base().ProblemDataManager::ATTACH_DIR.$file;
    }

    public function getTestdataInfo():array
    {
        //TODO: cache me~
        $files = $this->m_problem_data_manager->getTestdataFiles(true);

        $size = count($files);
        $res = [];
        for( $i=0 ; $i<$size ; $i+=2 )
        {
            $res [] = new Testcase\Data($i/2, $files[$i+1], $files[$i], $this->runtime_limit, $this->memory_limit);
        }
        return $res;
    }

    public function setJudgeJson($string)
    {
        return $this->m_problem_data_manager->write('judge.json',$string);
    }

    public function checkSet_title(string $string):bool
    {
        if( !is_string($string) )
            throw new ContainerModifyException('type fail');
        if( strlen($string) > 200 )
            throw new ContainerModifyException('title len limit error');
        return true;
    }

    public function checkSet_judge_profile($val):bool
    {
        if( !\SkyOJ\Judge\JudgeProfileEnum::isValidValue($val) )
            throw new ContainerModifyException('no such JudgeProfileEnum type');
        return true;
    }

    public function checkSet_judge(string $class):bool
    {
        throw new ContainerModifyException('judge is unused');
    }

    public function checkSet_content_access($val):bool
    {
        if( !ProblemLevel::isValidValue($val) )
            throw new ContainerModifyException('no such ContentAccess type');
        return true;
    }

    public function checkSet_content_type($val):bool
    {
        if( !ContentTypenEnum::isValidValue($val) )
            throw new ContainerModifyException('no such ContentType type');
        return true;
    }

    public function checkSet_submit_access($val):bool
    {
        if( !ProblemSubmitLevel::isValidValue($val) )
            throw new ContainerModifyException('no such ProblemSubmitLevel type');
        return true;
    }

    public function checkSet_runtime_limit($val):bool
    {
        if( !ctype_digit((string)$val) )
            throw new ContainerModifyException('runtime_limit shoudld be interger');
        return true;
    }

    public function checkSet_memory_limit($val):bool
    {
        if( !ctype_digit((string)$val) )
            throw new ContainerModifyException('memory_limit shoudld be interger');
        return true;
    }

    public function checkSet_codeview_access($val):bool
    {
        //if( ProblemLevel::isValidValue($val) )
        //    throw new ContainerModifyException('no such ContentAccess type');
        return true;
    }

    public function checkSet_admmsg($val):bool
    {
        return true;
    }

    public function checkSet_score_type($val):bool
    {
        if( !\SkyOJ\Score\ScoreModeEnum::isValidValue($val) )
            throw new ContainerModifyException('no such Score type');
        return true;
    }

    public function checkSet_score_data($val):bool
    {
        if( strlen($val) >= 1000 )
            throw new ContainerModifyException('score data size should less than 1000');
        return true;
    }
}

class ContainerException extends \Exception { }
class ContainerModifyException extends \Exception { }
