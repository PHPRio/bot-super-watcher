<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Models\Chats;
use Admin\Models\ChatsRules;
use Admin\Traits\Validate;
use Telegram\Bot\Exceptions\TelegramSDKException;

class DeleteRuleCommand extends Command
{
    use Validate;
    /**
     * @var string Command Name
     */
    protected $name = "deleterule";
    
    /**
     * @var string Command Description
     */
    protected $description = "Delete rule";

    /**
     * @var string Command Argument Pattern
     */
    protected $pattern = '{rule_name}';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
            'user_id' => $this->getUpdate()->getMessage()->getFrom()->getId()
        ]);
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            return;
        }

        $arguments = $this->getArguments();
        $errorMessage = '';
        if (!array_key_exists('rule_name', $arguments)) {
            $errorMessage = 'Rule not found';
        }
        if (!in_array($arguments['rule_name'], ['ban', 'deleteAudio'])) {
            $errorMessage = 'Rule not found';
        }
        if ($errorMessage) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'text' => $errorMessage
            ]);
            $this->getTelegram()->stop = true;
            return;
        }

        $chatRules = new ChatsRules();
        $chatRules->deleteRule($this->getUpdate()->getMessage()->getChat()->getId(), $arguments['rule_name']);
    }
}