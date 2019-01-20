<?php
namespace Admin;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

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
    public function __construct(Api $telegram) {
        $this->telegram = $telegram;
        $this->update = $telegram->getWebhookUpdate();
    }
    
    public function ban()
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
                $this->telegram->kickChatMember([
                    'chat_id' => $this->update->getChat()->getId(),
                    'user_id' => $isBanned->id
                ]);
                $this->telegram->deleteMessage([
                    'chat_id' => $this->update->getChat()->getId(),
                    'message_id' => $message->getMessageId()
                ]);
                $this->telegram->sendMessage([
                    'chat_id' => $this->update->getChat()->getId(),
                    'text' => 'Menos um cheater!!!'
                ]);
                if(getenv('ADMIN_GROUP')) {
                    $this->telegram->sendMessage([
                        'chat_id' => getenv('ADMIN_GROUP'),
                        'text' => "BAN! Ban! Ban!!!\n".print_r($isBanned, true)
                    ]);
                }
            }
        }
    }
    
    public function deleteAudio()
    {
        if(!$this->update->has('message')){
            return;
        }
        $message = $this->update->getMessage();
        if(!$message->has('voice')) {
            return;
        }
//         $this->telegram->deleteMessage([
//             'chat_id' => $this->update->getChat()->getId(),
//             'message_id' => $message->getMessageId()
//         ]);
        $this->telegram->sendVoice([
            'chat_id' => $this->update->getChat()->getId(),
            'voice' => $message->getVoice()->getFileId(),
            'caption' => print_r([$message->getFrom(),$message->getChat()], true)
        ]);
    }
}