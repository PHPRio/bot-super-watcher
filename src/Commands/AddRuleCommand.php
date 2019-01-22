<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Models\Chats;
use Admin\Traits\Validate;

class AddRuleCommand extends Command
{
    use Validate;
    /**
     * @var string Command Name
     */
    protected $name = "addrule";
    
    /**
     * @var string Command Description
     */
    protected $description = "Add new rule";
    
    /**
     * @inheritdoc
     */
    public function handle()
    {
    }
}