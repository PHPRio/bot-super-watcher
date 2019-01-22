<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Models\Chats;

class NewChatMemberCommand extends Command
{
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
            return;
        }
    }
}