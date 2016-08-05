<?php namespace SKYOJ;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
//Get Max Page id
//Get Min Page id (1)
//show range +-3
class PageList
{
    private $table;
    private $allrow;
    private $quest;
    const ROW_PER_PAGE = 20;
    const PAGE_RANGE = 3; //> it will show +-PAGE_RANGE, if now at 5, it shiw 234 5 678

    private function update()
    {
        $res = \DB::fetch("SELECT COUNT(*) FROM `{$this->table}` WHERE {$this->quest}");
        if ($res === false) {
            throw new \Exception('SQL Error');
        }
        $this->allrow = (int) $res[0];
    }

    public function all():int
    {
        if ($this->allrow == 0) {
            return 1;
        }

        return ceil($this->allrow / self::ROW_PER_PAGE);
    }

    public function __construct(string $t, string $quest = '1')
    {
        $this->quest = $quest;
        $this->table = \DB::tname($t);
        $this->update();
    }

    public function min(int $d):int
    {
        return max(1, $d - self::PAGE_RANGE);
    }

    public function max(int $d):int
    {
        return min($this->all(), $d + self::PAGE_RANGE);
    }

    public function left(int $d)
    {
        return max($d - 1, $this->min($d));
    }

    public function right(int $d)
    {
        return min($d + 1, $this->max($d));
    }
}
