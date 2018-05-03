<?php


use Phinx\Migration\AbstractMigration;

class Scoreboard extends AbstractMigration
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
        $table = $this->table('scoreboard',['id' => 'sb_id']);
        $table  ->addColumn('title','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('owner','integer')
                ->addColumn('type','integer')
                ->addColumn('type_note','text',['null'=>true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('timestamp','timestamp',['update'=>'CURRENT_TIMESTAMP','default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('start','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('end','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('json','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('problems','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('announce','text',['null'=>true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('state','integer')
                ->addIndex(['sb_id'],['unique'=>true,'name'=>'id'])
                ->create();
    }
}
