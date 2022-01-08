<?php
use Telegram\Bot\Objects\Update;
use Admin\Rules;
use Admin\Api;
use League\Event\Emitter;
use Telegram\Bot\Events\UpdateWasReceived;

require_once 'vendor/autoload.php';

if(file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__);
    $dotenv->load();
}

if(getenv('MOCK_JSON')) {
    class mockApi extends Api {
        public function getWebhookUpdate($shouldEmitEvent = true): Update {
            $content = trim($_ENV['MOCK_JSON'], "'");
            $update = new Update(json_decode($content, true));
            if ($shouldEmitEvent) {
                $this->emitEvent(new UpdateWasReceived($update, $this));
            }
            return $update;
        }
    }
    $telegram = new mockApi();
} else {
    error_log(file_get_contents('php://input'));
    $telegram = new Api();
}

$telegram->addCommand(Admin\Commands\NewChatMemberCommand::class);
$telegram->addCommand(Admin\Commands\StartCommand::class);
$telegram->addCommand(Admin\Commands\TextCommand::class);
$telegram->addCommand(Admin\Commands\AddRuleCommand::class);

$emitter = new Emitter();
$emitter->addListener(UpdateWasReceived::class, function ($event) {
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
});
$telegram->setEventEmitter($emitter);

$telegram->commandsHandler(true);