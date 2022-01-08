<?php
namespace Admin\Traits;

use Telegram\Bot\Exceptions\TelegramSDKException;

trait Validate
{
    public function exitIfBotIsNotAdmin() {
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();
        $userId = $this->getTelegram()->getMe()->getId();
        $chatMember = $this->getChatMember($chatId, $userId);
        // if the bot don't is admin, notify
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'parse_mode' => 'markdown',
                'text' =>
                    "I need a power! Please! Before anything: promote me to admin!\n\n".
                    'Another question: '.
                        '[associate me]'.
                        '(https://t.me/'.$this->getTelegram()->getMe()->getUsername().'/?startgroup='.
                            $this->getUpdate()->getMessage()->getChat()->getId().
                        ') to group that you want to manage, '.
                    "and delete this message after.\n\n".
                    'You must be admin in the other group as well. '.
                    'If you do not have an administrative group, you will not receive administrative action logs.'
            ]);
            $this->getTelegram()->stop = true;
            throw new TelegramSDKException('Is not admin');
        }
    }

    public function exitIfIsNotchat() {
        if(!$this->getUpdate()->getMessage()->getChat() || !in_array($this->getUpdate()->getMessage()->getChat()->getType(), ['group', 'supergroup'])) {
            $this->getTelegram()->stop = true;
            throw new TelegramSDKException('Is not chat');
        }
    }

    /**
     * If the current user don't is admin, exit from chat
     *
     * @return boolean
     */
    public function exitIfUserNotAdmin() {
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();
        $userId = $this->getUpdate()->getMessage()->getFrom()->getId();
        $chatMember = $this->getChatMember($chatId, $userId);
        // if the current user don't is admin, exit from chat
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Sorry! Only administrators can add me to chats.'
            ]);
            $this->getTelegram()->leaveChat(['chat_id' => $chatId]);
            $this->getTelegram()->stop = true;
            throw new TelegramSDKException('User is not admin');
        }
    }

    public function exitIfUserIsNotTheBot() {
        $member = $this->getUpdate()->getMessage()->getFrom();
        if($member->getId() != $this->getTelegram()->getMe()->getId()) {
            throw new TelegramSDKException('User is not the bot');
        }
    }

    private function getChatMember($chatId, $memberId) {
        $chatMember = $this->getCache()->get('chat_member:'.$chatId . '#' . $memberId);
        if ($chatMember) {
            return $chatMember;
        }
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $chatId,
            'user_id' => $memberId
        ]);
        $this->getCache()->set('chat_member:'.$chatId . '#' . $memberId, $chatMember);
        return $chatMember;
    }
}