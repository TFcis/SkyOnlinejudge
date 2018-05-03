<?php


use Phinx\Migration\AbstractMigration;

class Codepad extends AbstractMigration
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
        $table = $this->table('codepad');
        $table  ->addColumn('owner','integer')
                ->addColumn('hash','string',['limit'=>30,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('type','integer')
                ->addColumn('timestamp','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('content','text',['null'=>true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addIndex(['hash'],['unique'=>true,'name'=>'hash'])
                ->create();
    }
}
