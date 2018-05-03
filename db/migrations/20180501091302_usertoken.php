<?php


use Phinx\Migration\AbstractMigration;

class Usertoken extends AbstractMigration
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
        $table = $this->table('usertoken',['id' => false]);
        $table  ->addColumn('uid','integer')
                ->addColumn('timeout','integer')
                ->addColumn('type','text',['encoding'=>'utf8','collation'=>'utf8_unicode_ci'])
                ->addColumn('token','text',['encoding'=>'utf8','collation'=>'utf8_unicode_ci'])
                ->create();
    }
}
