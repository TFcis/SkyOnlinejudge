<?php


use Phinx\Migration\AbstractMigration;

class JudgeProfile extends AbstractMigration
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
        $table = $this->table('judge_profile');
        $table  ->addColumn('text','string',['limit'=>64,'encoding'=>'utf8','collation'=>'utf8_general_ci'])
                ->addColumn('judge','integer')
                ->addColumn('profile','text',['encoding'=>'utf8','collation'=>'utf8_general_ci'])
                ->addIndex(['text'],['unique'=>true,'name'=>'text'])
                ->create();
    }
}
