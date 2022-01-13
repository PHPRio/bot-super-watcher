<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Traits\Validate;
use Telegram\Bot\Exceptions\TelegramSDKException;

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
    protected $description = "New chat member joined";
    
    /**
     * @inheritdoc
     */
    public function handle()
    {
        try {
            $this->exitIfUserIsNotTheBot();
            $this->exitIfIsNotChat();
            $this->exitIfUserNotAdmin();
            $this->exitIfBotIsNotAdmin();
        } catch (TelegramSDKException $th) {
            //throw $th;
        }
    }
}