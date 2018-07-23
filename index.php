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
    $filename = getenv('METADATA_FILE');
    $metadata = json_decode(file_get_contents(getenv('METADATA_FILE')));

    foreach($metadata->byRegex as $regex) {
        if(preg_match('/'.$regex.'/', $rawUser->first_name)) {
            $isBanned = $rawUser;
            break;
        }
    };
    if(!$isBanned) {
        foreach($metadata->banned as $banned) {
            if($rawUser == $banned) {
                $isBanned = $rawUser;
                break;
            }
        };
    }
    if($isBanned) {
       $telegram->kickChatMember([
           'chat_id' => $update->getChat()->getId(),
           'user_id' => $isBanned->id
       ]);
       $telegram->deleteMessage([
           'chat_id' => $update->getChat()->getId(),
           'message_id' => $message->getMessageId()
       ]);
       $telegram->sendMessage([
           'chat_id' => $update->getChat()->getId(),
           'text' => 'Menos um cheater!!!'
       ]);
       if(getenv('ADMIN_GROUP')) {
           $telegram->sendMessage([
               'chat_id' => getenv('ADMIN_GROUP'),
               'text' => "BAN! Ban! Ban!!!\n".print_r($isBanned, true)
           ]);
       }
    }
    
}