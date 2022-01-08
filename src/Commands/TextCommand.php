<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Models\Chats;

class TextCommand extends Command
{
    /**
     * @var string Command Name
     */
    
    protected $name = "text";
    
    /**
     * @var string Command Description
     */
    protected $description = "Check if bot is admin";
    
    /**
     * @inheritdoc
     */
    public function handle()
    {
        $message = $this->getUpdate()->getMessage();
        // dont parse commands
        if($this->getCommandBus()->parseCommand($message->getText())) {
            return;
        }
        // dont parse messages from this bot
        if($message->getFrom()->getId() == $this->getTelegram()->getMe()->getId()) {
            return;
        }
        if(!$this->issAdmin()) {
            return;
        }
    }
    private function issAdmin()
    {
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
            'user_id' => $this->getTelegram()->getMe()->getId()
        ]);
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'text' => 'I need a power! Please! Promote me to admin!'
            ]);
            $this->getTelegram()->stop = true;
            return false;
        }
        return true;
    }
}