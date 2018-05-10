<?php


use Phinx\Migration\AbstractMigration;

class Plugin extends AbstractMigration
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
        $table = $this->table('plugin');
        $table  ->addColumn('class','string',['limit'=>64,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('version','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('name','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('description','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('copyright','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('timestamp','timestamp',['update'=>'CURRENT_TIMESTAMP','default'=>'CURRENT_TIMESTAMP'])
                ->addIndex(['class'],['unique'=>true,'name'=>'class'])
                ->create();
    }
}
