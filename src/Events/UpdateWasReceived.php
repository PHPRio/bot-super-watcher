<?php

namespace Admin\Events;

use Admin\Models\Chats;
use Admin\Rules;
use Telegram\Bot\Events\UpdateWasReceived as EventsUpdateWasReceived;

/**
 * Class UpdateWasReceived.
 */
class UpdateWasReceived extends EventsUpdateWasReceived
{
    public function handle($event) {
        $type = $event->getUpdate()->detectType();
        switch ($type) {
            case 'message':
                if ($event->getUpdate()->getMessage()->hasCommand()) {
                    return;
                }
                $Chats = new Chats();
                $chat = $Chats->getChatById($this->getUpdate()->getMessage()->getChat()->getId());
                if (!$chat) {
                    $this->getTelegram()->sendMessage([
                        'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                        'parse_mode' => 'markdown',
                        'text' => 'I need power! Give-me admin rule!',
                    ]);
                    $this->getTelegram()->stop = true;
                    return;
                }
                break;
        }
        $rules = new Rules($event->getTelegram());
        $rules->apply();
    }
}
