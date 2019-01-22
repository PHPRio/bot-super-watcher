<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Traits\Validate;

class NewChatMemberCommand extends Command
{
    use Validate;
    /**
     * @var string Command Name
     */
    protected $name = "new_chat_member";
    
    /**
     * @var string Command Description
     */
    protected $description = "New chaht member joined";
    
    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $member = $this->getUpdate()->getMessage()->getFrom();
        if($member->getId() != $this->getTelegram()->getMe()->getId()) {
            return;
        }
        $this->validateStart($this->getTelegram(), $this->getUpdate());
    }
}