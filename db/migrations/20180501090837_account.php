<?php


use Phinx\Migration\AbstractMigration;

class Account extends AbstractMigration
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
        $table = $this->table('account',['id' => 'uid']);
        $table  ->addColumn('email','string',['limit'=>64,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('passhash','string',['limit'=>200,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('nickname','string',['limit'=>64,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('timestamp','timestamp',['default'=>'CURRENT_TIMESTAMP'])
                ->addColumn('realname','string',['limit'=>30,'null' => true,'encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('level','integer')
                ->addIndex(['email'],['name'=>'email'])
                ->addIndex(['nickname'],['name'=>'nickname'])
                ->create();
    }
}
