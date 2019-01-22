<?php


use Phinx\Migration\AbstractMigration;

class ChatRule extends AbstractMigration
{
    public function change()
    {
        $this->table('chat_rule')
            ->addColumn('chat_id', 'string')
            ->addColumn('rule', 'string')
            ->addForeignKey('chat_id', 'chat', 'chat_id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->save();
    }
}
