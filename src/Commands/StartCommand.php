<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Models\Chats;
use Admin\Traits\Cache;
use Admin\Traits\Validate;
use Telegram\Bot\Exceptions\TelegramSDKException;

class StartCommand extends Command
{
    use Cache;
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
    public function handle()
    {
        $message = $this->update->getMessage();
        $chat_id = $message->getchat()->getId();
        if(!$chat_id) {
            return;
        }
        if (!$this->validateStart($this->getTelegram(), $this->getUpdate())) {
            return;
        }
        $chats = new Chats();
        $chat = $chats->getChatById($chat_id);
        // parent exists...
        if($chat) {
            $chats->getConnection()->update('chat',
                ['chat_id' => $chat_id],
                ['admin_chat_id' => $this->getUpdate()->getMessage()->getChat()->getId()]
            );
            $chat['admin_chat_id'] = $this->getUpdate()->getMessage()->getChat()->getId();
            $this->getCache()->set('chat_id:'.$chat_id, $chat);
        } else {
            $chats->getConnection()->insert('chat', [
                'admin_chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'chat_id' => $chat_id
            ]);
        }
        try {
            $this->getTelegram()->sendMessage([
                'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'text' =>
                    'Good work!'
            ]);
        } catch (TelegramSDKException $th) {
        }
    }
}