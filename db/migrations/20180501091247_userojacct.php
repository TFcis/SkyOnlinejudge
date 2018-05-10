<?php


use Phinx\Migration\AbstractMigration;

class Userojacct extends AbstractMigration
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
        $table = $this->table('userojacct',['id' => false]);
        $table  ->addColumn('uid','integer')
                ->addColumn('id','integer')
                ->addColumn('account','string',['limit'=>64,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('approve','integer')
                ->addIndex(['uid','id'],['unique'=>true,'name'=>'uid'])
                ->create();
    }
}
