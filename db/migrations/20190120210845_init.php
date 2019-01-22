<?php


use Phinx\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    public function change()
    {
        $this->table('chat')
            ->addColumn('chat_id', 'string')
            ->addColumn('admin_chat_id', 'string', ['null' => true])
            ->addColumn('deleted', 'datetime', ['null' => true])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['chat_id'], ['unique' => true])
            ->create();
    }
}
