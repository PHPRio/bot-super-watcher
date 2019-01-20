<?php
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use Admin\Rules;

require_once 'vendor/autoload.php';

if(file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::create(__DIR__);
    $dotenv->overload();
    $dotenv->required(['TELEGRAM_BOT_TOKEN', 'METADATA_FILE']);
}

if(getenv('MOCK_JSON')) {
    class mockApi extends Api {
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

$rules = new Rules($telegram);

$rules->ban();
$rules->deleteAudio();