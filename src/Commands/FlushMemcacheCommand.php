<?php

namespace Admin\Commands;

use Admin\Traits\Cache;
use Telegram\Bot\Commands\Command;
use Admin\Traits\Validate;
use Telegram\Bot\Exceptions\TelegramSDKException;

class FlushMemcacheCommand extends Command
{
    use Cache;
    use Validate;
    /**
     * @var string Command Name
     */
    protected $name = "flush";
    
    /**
     * @var string Command Description
     */
    protected $description = "Flush memcache";
    
    /**
     * @inheritdoc
     */
    public function handle()
    {
        try {
            $this->exitIfIsNotChat();
            $this->exitIfUserNotAdmin();
            $this->exitIfBotIsNotAdmin();
            $this->getCache()->flush();
        } catch (TelegramSDKException $th) {
        }
    }
}