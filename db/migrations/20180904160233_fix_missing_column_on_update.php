<?php


use Phinx\Migration\AbstractMigration;

class FixMissingColumnOnUpdate extends AbstractMigration
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
        //account
        $table = $this->table('account',['id' => 'uid']);
        if( !$table->hasColumn('level') )    $table->addColumn('level','integer');
        if( !$table->hasColumn('allow_ip') ) $table->addColumn('allow_ip','text');
        $table->save();

        //problem
        $table = $this->table('problem',['id' => 'pid']);
        if( !$table->hasColumn('content_type') ) $table->addColumn('content_type','integer');
        if( !$table->hasColumn('judge_profile') ) $table->addColumn('judge_profile','integer');
        if( !$table->hasColumn('memory_limit') ) $table->addColumn('memory_limit','integer');
        if( !$table->hasColumn('runtime_limit') ) $table->addColumn('runtime_limit','integer');
        $table->save();
    }
}
