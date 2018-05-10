<?php


use Phinx\Migration\AbstractMigration;

class ScoreboardUsers extends AbstractMigration
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
        $table = $this->table('scoreboard_users',['id' => false]);
        $table  ->addColumn('sb_id','integer')
                ->addColumn('uid','integer')
                ->addIndex(['sb_id','uid'],['unique'=>true,'name'=>'sb_id'])
                ->addIndex(['sb_id'],['name'=>'sb_id_2'])
                ->create();
    }
}
