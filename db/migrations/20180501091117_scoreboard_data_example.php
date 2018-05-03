<?php


use Phinx\Migration\AbstractMigration;

class ScoreboardDataExample extends AbstractMigration
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
        $table = $this->table('scoreboard_data_example',['id' => false]);
        $table  ->addColumn('sb_id','integer')
                ->addColumn('uid','integer')
                ->addColumn('pid','integer')
                ->addColumn('result','integer')
                ->addColumn('score','float')
                ->addIndex(['sb_id'],['name'=>'sb_id'])
                ->create();
    }
}
