<?php

namespace Admin\Commands;

use Telegram\Bot\Commands\Command;
use Admin\Models\Chats;
use Admin\Models\ChatsRules;
use Admin\Traits\Cache;
use Admin\Traits\Validate;
use Telegram\Bot\Exceptions\TelegramSDKException;

class AddRuleCommand extends Command
{
    use Cache;
    use Validate;
    /**
     * @var string Command Name
     */
    protected $name = "addrule";
    
    /**
     * @var string Command Description
     */
    protected $description = "Add new rule";

    /**
     * @var string Command Argument Pattern
     */
    protected $pattern = '{rule_name}';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        try {
            $this->exitIfIsNotchat();
            $this->exitIfUserNotAdmin();
            $this->exitIfBotIsNotAdmin();
        } catch (TelegramSDKException $th) {
            return;
        }
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
        $chatRules->addRule($this->getUpdate()->getMessage()->getChat()->getId(), $arguments['rule_name']);
    }

    public function validate(): bool {
        $chatMember = $this->getTelegram()->getChatMember([
            'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
            'user_id' => $this->getUpdate()->getMessage()->getFrom()->getId()
        ]);
        $arguments = $this->getArguments();
        $errorMessage = '';
        if (!in_array($chatMember->get('status'), ["creator", "administrator"])) {
            $errorMessage = 'You need be a creator or admin';
        } elseif (!array_key_exists('rule_name', $arguments)) {
            $errorMessage = 'Rule not found';
        } elseif (!in_array($arguments['rule_name'], ['ban', 'deleteAudio'])) {
            $errorMessage = 'Rule not found';
        }
        if ($errorMessage) {
            $this->getTelegram()->sendMessage([
                'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'text' => $errorMessage
            ]);
            $this->getTelegram()->stop = true;
            return false;
        }
        return true;
    }
}