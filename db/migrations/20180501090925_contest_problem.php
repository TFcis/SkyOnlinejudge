<?php


use Phinx\Migration\AbstractMigration;

class ContestProblem extends AbstractMigration
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
        $table = $this->table('contest_problem',['id'=>false]);
        $table  ->addColumn('cont_id','integer')
                ->addColumn('pid','integer')
                ->addColumn('ptag','text',['encoding'=>'utf8','collation'=>'utf8_bin'])
                ->addColumn('state','integer')
                ->addColumn('priority','integer')
                ->addIndex(['cont_id','pid'],['unique'=>true,'name'=>'cont_id'])
                ->create();
    }
}
