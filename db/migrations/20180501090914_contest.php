<?php


use Phinx\Migration\AbstractMigration;

class Contest extends AbstractMigration
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
        $table = $this->table('contest',['id'=>'cont_id']);
        $table  ->addColumn('owner','integer')
                ->addColumn('timestamp','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('starttime','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('endtime','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('class','string',['limit'=>64,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('title','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('register_type','integer')
                ->addColumn('register_beginsec','integer',['default'=>3600])
                ->addColumn('register_delaysec','integer',['default'=>0])
                ->addColumn('freeze_sec','integer',['default'=>3600])
                ->addColumn('freezestate','integer',['default'=>0])
                ->addColumn('penalty','integer',['default'=>1200])
                ->addColumn('randproblem','integer',['default'=>0])
                ->create();
    }
}
