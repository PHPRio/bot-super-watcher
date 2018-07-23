<?php
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

require_once 'vendor/autoload.php';

if(file_exists('.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}

if(getenv('MOCK_JSON')) {
    class mockApi extends Api{
        public function getWebhookUpdate($shouldEmitEvent = true) {
            $content = trim(getenv('MOCK_JSON'), "'");
            return new Update(json_decode($content, true));
        }
    }
    $telegram = new mockApi();
} else {
    error_log(file_get_contents('php://input'));
    $telegram = new Api();
}

$update = $telegram->getWebhookUpdates();

$message = $update->getMessage();
if($user = $message->getNewChatMember()) {
    $rawUser = (object)$user->getRawResponse();
    $metadata = json_decode(file_get_contents(getenv('METADATA_FILE')));

    $isBanned = array_filter($metadata->banned, function($banned) use ($rawUser) {
        if($rawUser == $banned) {
            return $banned;
        }
    });
    if($isBanned) {
       $telegram->kickChatMember([
           'chat_id' => $update->getChat()->getId(),
           'user_id' => $isBanned[0]->id
       ]);
    }
}