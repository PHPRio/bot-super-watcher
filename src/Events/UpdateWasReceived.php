<?php

namespace Admin\Events;

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
                break;
        }
        $rules = new Rules($event->getTelegram());
        $rules->apply();
    }
}
