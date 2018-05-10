<?php


use Phinx\Migration\AbstractMigration;

class Challenge extends AbstractMigration
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
        $table = $this->table('challenge',['id' => 'cid']);
        $table  ->addColumn('pid','integer')
                ->addColumn('uid','integer')
                ->addColumn('code','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('comment','text',['null'=>true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('compiler','string',['limit'=>60,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('result','integer')
                ->addColumn('runtime','integer')
                ->addColumn('memory','integer')
                ->addColumn('score','integer',['default'=>0])
                ->addColumn('timestamp','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('package','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('language','string',['limit'=>25,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addIndex(['cid'],['name'=>'cid'])
                ->addIndex(['pid'],['name'=>'pid'])
                ->addIndex(['uid'],['name'=>'uid'])
                ->addIndex(['result'],['name'=>'result'])
                ->create();
    }
}
