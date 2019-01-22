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
    protected $description = "Text";
    
    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $message = $this->getUpdate()->getMessage();
        $member = $message->getFrom();
        if($member->getId() != $this->getTelegram()->getMe()->getId()) {
            return;
        }
        $chat = new Chats();
        $chat_id = $message->getChat()->getId();
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $chat_id,
            'user_id' => $message->getFrom()->getId()
        ]);
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Sorry! Only admins can add me to this chat'
            ]);
            $this->getTelegram()->leaveChat(['chat_id' => $chat_id]);
            $this->getTelegram()->leave_chat = true;
            $chat->getConnection()->delete('chat', [
                'chat_id' => $chat_id
            ]);
            return;
        }
        $this->getTelegram()->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'I need a power! Please! Promote me to admin!'
        ]);
        $chat->getConnection()->insert('chat', [
            'chat_id' => $chat_id
        ]);
    }
}