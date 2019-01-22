<?php
namespace Admin\Models;

use Admin\Db;
use Admin\Traits\Cache;

class ChatsRules extends Db
{
    use Cache;
    public function getRules($chat_id)
    {
        $rules = $this->getCache()->fetch('chat_rule_chat_id:'.$chat_id);
        if ($rules) {
            return $rules;
        }
        $connection = $this->getConnection();
        $stmt = $connection->prepare('SELECT rule FROM chat_rule WHERE chat_id = :chat_id');
        $stmt->execute(['chat_id' => $chat_id]);
        $rules = $stmt->fetchAll();
        if ($rules) {
            $this->getCache()->save('chat_rule_chat_id:'.$chat_id, $rules);
        }
        return $rules;
    }
}