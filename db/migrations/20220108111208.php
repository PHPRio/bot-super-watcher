<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class V20220108111208 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('chat')
            ->addColumn('title', 'string', ['null' => true])
            ->addColumn('username', 'string', ['null' => true])
            ->addColumn('type', 'string', ['null' => true])
            ->save();
    }
}
