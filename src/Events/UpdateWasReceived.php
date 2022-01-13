<?php

namespace Admin\Events;

use Admin\Models\Chats;
use Admin\Rules;
use Admin\Traits\Cache;
use Admin\Traits\Validate;
use Telegram\Bot\Events\UpdateWasReceived as EventsUpdateWasReceived;
use Telegram\Bot\Exceptions\TelegramSDKException;

/**
 * Class UpdateWasReceived.
 */
class UpdateWasReceived extends EventsUpdateWasReceived
{
    use Cache;
    use Validate;
    public function handle($event) {
        try {
            syslog(LOG_WARNING, 'Start update');
            $type = $event->getUpdate()->detectType();
            switch ($type) {
                case 'message':
                    if ($event->getUpdate()->getMessage()->hasCommand()) {
                        return;
                    }
                    $this->exitIfBotIsNotAdmin();
                    break;
            }
            $rules = new Rules($event->getTelegram());
            $rules->apply();
        } catch (TelegramSDKException $th) {
            return;
        }
    }
}
