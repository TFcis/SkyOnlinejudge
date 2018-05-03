<?php


use Phinx\Migration\AbstractMigration;

class Syslog extends AbstractMigration
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
        $table = $this->table('syslog');
        $table  ->addColumn('timestamp','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('level','string',['null'=>true,'limit'=>64,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('message','text',['null'=>true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->create();
    }
}
