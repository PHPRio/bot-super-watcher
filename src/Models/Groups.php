<?php
namespace Admin\Models;

use Admin\Db;
use Admin\Traits\Cache;

class Groups extends Db
{
    use Cache;
    public function getChatById($id)
    {
        return getenv('ADMIN_GROUP');
        $chat = $this->getCache()->fetch('chat_id:'.$id);
        if ($chat) {
            return $chat;
        }
        $connection = $this->getConnection();
        $stmt = $connection->prepare('SELECT * FROM groups WHERE chat_id = :chat_id');
        $stmt->execute(['chat_id' => $id]);
        $chat = $stmt->fetch();
        $this->getCache()->save('chat_id:'.$id, $chat);
        return $chat;
    }
}