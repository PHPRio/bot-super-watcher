<?php
namespace Admin\Traits;

use Admin\Api;
use Telegram\Bot\Objects\Update;

trait Validate
{
    public function validateStart(Api $telegram, Update $update)
    {
        $message = $update->getMessage();
        $chat_id = $message->getChat()->getId();
        $chatMember = $telegram->getChatMember([
            'chat_id' => $chat_id,
            'user_id' => $message->getFrom()->getId()
        ]);
        // if the current user don't is admin, exit from chat
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Sorry! Only administrators can add me to chats.'
            ]);
            $telegram->leaveChat(['chat_id' => $chat_id]);
            $telegram->stop = true;
            return false;
        }
        $botChatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
            'user_id' => $this->getTelegram()->getMe()->getId()
        ]);
        // if the bot don't is admin, notify
        if (!in_array($botChatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'parse_mode' => 'markdown',
                'text' =>
                    "I need a power! Please! Before anything: promote me to admin!\n\n".
                    'Another question: If this is the group you want to manage, '.
                        '[associate me]'.
                        '(https://t.me/'.$this->getTelegram()->getMe()->getUsername().'/?startgroup='.
                            $message->getChat()->getId().
                        ') to a admin group '.
                    "and delete this message after.\n\n".
                    'You must be admin in the other group as well. '.
                    'If you do not have an administrative group, you will not receive administrative action logs.'
            ]);
            $this->getTelegram()->stop = true;
            return false;
        }
        return true;
    }
}