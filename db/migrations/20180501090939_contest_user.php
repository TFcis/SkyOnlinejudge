<?php


use Phinx\Migration\AbstractMigration;

class ContestUser extends AbstractMigration
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
        $table = $this->table('contest_user',['id'=>false]);
        $table  ->addColumn('cont_id','integer')
                ->addColumn('uid','integer')
                ->addColumn('team_id','integer')
                ->addColumn('state','integer')
                ->addColumn('timestamp','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('note','text',['null'=>true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addIndex(['cont_id','uid'],['unique'=>true,'name'=>'cond_id'])
                ->create();
    }
}
