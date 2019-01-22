<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;

class TextCommand extends Command
{
    /**
     * @var string Command Name
     */
    
    protected $name = "text";
    
    /**
     * @var string Command Description
     */
    protected $description = "Text";
    
    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $message = $this->getUpdate()->getMessage();
        if($message->getFrom()->getId() == $this->getTelegram()->getMe()->getId()) {
            return;
        }
        $chat_id = $message->getChat()->getId();
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $chat_id,
            'user_id' => $this->getTelegram()->getMe()->getId()
        ]);
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $chat_id,
                'text' => 'I need a power! Please! Promote me to admin!'
            ]);
            $this->getTelegram()->stop = true;
            return;
        }
    }
}