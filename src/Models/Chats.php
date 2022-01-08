<?php
namespace Admin\Models;

use Admin\Db;
use Admin\Traits\Cache;

class Chats extends Db
{
    use Cache;
    public function getChatById($id)
    {
        $chat = $this->getCache()->get('chat_id:'.$id);
        if ($chat) {
            return $chat;
        }
        $connection = $this->getConnection();
        $stmt = $connection->prepare('SELECT * FROM chat WHERE chat_id = :chat_id');
        $result = $stmt->executeQuery(['chat_id' => $id]);
        $chat = $result->fetchAssociative();
        if ($chat) {
            $this->getCache()->set('chat_id:'.$id, $chat, time() + 60*10); // 10 min
        }
        return $chat;
    }
}