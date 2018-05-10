<?php


use Phinx\Migration\AbstractMigration;

class ScoreboardProblems extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('scoreboard_problems',['id' => false]);
        $table  ->addColumn('sb_id','integer')
                ->addColumn('ord','integer')
                ->addColumn('problem','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('note','text',['null'=>true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addIndex(['sb_id','ord'],['unique'=>true,'name'=>'sb_id'])
                ->addIndex(['sb_id'],['name'=>'sb_id_2'])
                ->create();
    }
}
