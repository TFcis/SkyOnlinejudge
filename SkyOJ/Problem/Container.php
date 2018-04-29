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
use \SkyOJ\File\ProblemManager;
use \SkyOJ\Judge\JudgeProfileEnum;

class Container extends \SkyOJ\Core\CommonObject implements \SkyOJ\Core\Permission\Permissible
{
    protected static $table = 'problem'; 
    protected static $prime_key = 'pid';
    private $m_problem_manager;
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
            'content_type' => ProblemDescriptionEnum::MarkDown
        ];
        return self::insertInto($default);
    }

    protected function afterLoad()
    {
        $this->m_problem_manager = new ProblemManager($this->pid, true);
        $this->m_content = Content::init($this->content_type, $this->m_problem_manager);
        $this->json = json_decode($this->m_problem_manager->read(ProblemManager::PROBLEM_JSON_FILE),true);
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
        return $this->readable($user) && $this->isSubmitFuncOpen();
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
            $this->m_content = Content::init($this->content_type, $this->m_problem_manager);
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
        return $this->m_problem_manager->read('judge.json');
    }

    public function getFileManager()
    {
        return $this->m_problem_manager;
    }

    public function genAttachLocalPath(string $file)
    {
        if( !$this->m_problem_manager->checkFilename($file) )
            throw new ContainerException('FILENAME NOT AVAILABLE');
        return $this->m_problem_manager->base().ProblemManager::ATTACH_DIR.$file;
    }

    public function getTestdata():array
    {
        $files = $this->m_problem_manager->getTestdataFiles();
        $map = [];
        foreach($files as $file)
        {
            $info = pathinfo($file);
            if( !isset($map[$info['filename']]) )
                $map[$info['filename']] = [];
            if( in_array($info['extension'],ProblemManager::INPUT_EXT) )
                $map[$info['filename']][0] = $file;
            else if( in_array($info['extension'],ProblemManager::OUTPUT_EXT) )
                $map[$info['filename']][1] = $file;
        }

        $res = [];
        foreach($map as $io)
        {
            if(!empty($io))
            {
                $res[] = new Testdata($io[0]??NULL,$io[1]??NULL);
            }
        }
        return $res;
    }

    public function setJudgeJson($string)
    {
        return $this->m_problem_manager->write('judge.json',$string);
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
}

class ContainerException extends \Exception { }
class ContainerModifyException extends \Exception { }
