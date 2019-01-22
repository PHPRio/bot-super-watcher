<?php
namespace Admin;

use Telegram\Bot\Api as TelegramApi;
use Telegram\Bot\Objects\Update;

class Api extends TelegramApi
{
    /**
     * @param Update $update
     */
    public function processObject(Update $update)
    {
        $message = $update->getMessage();

        if ($message !== null) {
            $this->getCommandBus()->handler('/'.$message->detectType(), $update);
        }
    }
}