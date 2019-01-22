<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Models\Chats;
use Admin\Traits\Validate;

class StartCommand extends Command
{
    use Validate;
    /**
     * @var string Command Name
     */
    protected $name = "start";
    
    /**
     * @var string Command Description
     */
    protected $description = "Start this bot";
    
    /**
     * @inheritdoc
     */
    public function handle($chat_id)
    {
        if(!$chat_id) {
            return;
        }
        if (!$this->validateStart($this->getTelegram(), $this->getUpdate())) {
            return;
        }
        //  check if is admin in parent
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $chat_id,
            'user_id' => $this->getUpdate()->getMessage()->getFrom()->getId()
        ]);
        // if the current user don't is admin in parent, notify.
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $chat_id,
                'text' =>
                    'Sorry! You must be admin in the source chat. '.
                    'Request to admins to promote you or delegate this action to admins'
            ]);
            $this->getTelegram()->stop = true;
            return false;
        }
        //  check if the bot is admin in parent
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $chat_id,
            'user_id' => $this->getTelegram()->getMe()->getId()
        ]);
        // if the current user don't is admin in parent, notify.
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $chat_id,
                'text' =>
                    'Sorry! The must be admin in the source chat. '.
                    'Promote or delegate this action to admins.'
            ]);
            $this->getTelegram()->stop = true;
            return false;
        }
        $chats = new Chats();
        $chat = $chats->getChatById($chat_id);
        // parent exists...
        if($chat) {
            $chats->getConnection()->update('chat',
                ['chat_id' => $chat_id],
                ['admin_chat_id' => $this->getUpdate()->getMessage()->getChat()->getId()]
            );
        } else {
            $chats->getConnection()->insert('chat', [
                'admin_chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'chat_id' => $chat_id
            ]);
        }
        $this->getTelegram()->sendMessage([
            'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
            'text' =>
                'Good work!'
        ]);
    }
}