<?php


use Phinx\Migration\AbstractMigration;

class Problem extends AbstractMigration
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
        if( $this->hasTable('problem') ) #update from old
        {
            $table = $this->table('problem',['id' => 'pid']);
            $table  ->changeColumn('title','text',['encoding'=>'utf8','collation'=>'utf8_general_ci'])
                    ->changeColumn('class','string', ['limit'=>128,'encoding'=>'utf8','collation'=>'utf8_general_ci'])
                    ->save();
            return ;
        }
        $table = $this->table('problem',['id' => 'pid']);
        $table  ->addColumn('owner', 'integer')
                ->addColumn('content_access', 'integer')
                ->addColumn('submit_access', 'integer')
                ->addColumn('codeview_access','integer')
                ->addColumn('title','text',['encoding'=>'utf8','collation'=>'utf8_general_ci'])
                ->addColumn('class','string', ['limit'=>128,'encoding'=>'utf8','collation'=>'utf8_general_ci'])
                ->addColumn('judge_type','integer')
                ->save();
    }
}
