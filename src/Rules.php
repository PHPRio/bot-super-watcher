<?php
namespace Admin;

use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;


/**
 * 
 * @method Models\ChatsRules      getChatsRules() Return ChatsRules model.
 * @method Models\Chats           getChats()      Return Chats model.
 */
class Rules
{
    /**
     * @var Api
     */
    private $telegram;
    /**
     * @var Update
     */
    private $update;
    /**
     * @var array
     */
    private $models = [];
    public function __construct(Api $telegram) {
        $this->telegram = $telegram;
        $this->update = $this->telegram->getWebhookUpdate(false);
    }

    private function ban()
    {
        if($this->update->has('message')){
            $message = $this->update->getMessage();
            if($message->has('new_chat_member')) {
                $user = $message->getNewChatMember();
            }
        }
        if(!empty($user)) {
            $rawUser = (object)$user->getRawResponse();
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
                try {
                    $this->telegram->kickChatMember([
                        'chat_id' => $this->update->getChat()->getId(),
                        'user_id' => $isBanned->id
                    ]);
                    $this->telegram->deleteMessage([
                        'chat_id' => $this->update->getChat()->getId(),
                        'message_id' => $message->getMessageId()
                    ]);
                } catch (TelegramSDKException $th) {
                    //throw $th;
                }
                $chat = $this->getChats()->getChatById($this->update->getChat()->getId());
                if($chat && $chat['admin_chat_id']) {
                    $this->telegram->sendMessage([
                        'chat_id' => $chat['admin_chat_id'],
                        'text' => "BAN! Ban! Ban!!!\n".print_r($isBanned, true)
                    ]);
                }
            }
        }
    }

    public function __call(string $method, $arguments)
    {
        $action = substr($method, 0, 3);
        if ($action === 'get') {
            return $this->getModel('Admin\Models\\'.substr($method, 3));
        }
    }

    private function getModel($name)
    {
        if (empty($this->models[$name])) {
            $this->models[$name] = new $name();
        }
        return $this->models[$name];
    }

    private function deleteAudio()
    {
        if(!$this->update->has('message')){
            return;
        }
        $message = $this->update->getMessage();
        if(!$message->has('voice')) {
            return;
        }
        $adminGroup = $this->getChats()->getChatById($this->update->getChat()->getId());
        $adminGroupId = !empty($adminGroup['admin_chat_id'])?$adminGroup['admin_chat_id']:null;
        try {
            if($adminGroupId) {
                $this->telegram->sendVoice([
                    'chat_id' => $adminGroupId,
                    'voice' => $message->getVoice()->getFileId(),
                    'caption' => json_encode(json_decode($message), JSON_PRETTY_PRINT)
                ]);
            }
        } catch (\Exception $e) {}
        try {
            if($this->update->getChat()->getId() != $adminGroupId) {
                $this->telegram->deleteMessage([
                    'chat_id' => $this->update->getChat()->getId(),
                    'message_id' => $message->getMessageId()
                ]);
            }
        } catch (\Exception $e) {}
    }

    /**
     * @return \Admin\Rules
     */
    public function apply()
    {
        if(property_exists($this->telegram, 'stop')) {
            return;
        }
        $message = $this->update->get('message');
        if (!$message || !$message->has('chat')) {
            return;
        }
        $rules = $this->getChatsRules()->getRules($message->getChat()->getId());
        if ($rules) {
            foreach($rules as $rule) {
                $this->{$rule['rule']}();
            }
        }
        return $this;
    }

    /**
     * @return \Admin\Rules
     */
    public function assignNews()
    {
        return $this;
    }
}