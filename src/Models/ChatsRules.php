<?php
namespace Admin\Models;

use Admin\Db;
use Admin\Traits\Cache;

class ChatsRules extends Db
{
    use Cache;
    public function getRules($chat_id, $useCache = true)
    {
        if ($useCache) {
            $rules = $this->getCache()->get('chat_rule_chat_id:'.$chat_id);
            if ($rules) {
                return $rules;
            }
        }
        $connection = $this->getConnection();
        $stmt = $connection->prepare('SELECT rule FROM chat_rule WHERE chat_id = :chat_id');
        $result = $stmt->executeQuery(['chat_id' => $chat_id]);
        $rules = $result->fetchAllAssociative();
        if ($rules) {
            $this->getCache()->set('chat_rule_chat_id:'.$chat_id, $rules);
        }
        return $rules;
    }

    public function addRule($chatId, $rule) {
        $rules = $this->getRules($chatId);
        if ($rules) {
            $exists = array_filter($rules, function($r) use ($rule) {
                return $r['rule'] === $rule;
            });
            if ($exists) {
                return;
            }
        }
        $connection = $this->getConnection();
        $connection->executeStatement(
            'INSERT INTO chat_rule (chat_id, rule) VALUES (:chatId, :rule)',
            ['chatId' => $chatId, 'rule' => $rule]
        );
        $this->getRules($chatId, false);
    }

    public function deleteRule($chatId, $rule) {
        $connection = $this->getConnection();
        $connection->executeStatement(
            'DELETE FROM chat_rule WHERE chat_id = :chatId AND rule = :rule',
            ['chatId' => $chatId, 'rule' => $rule]
        );
        $this->getRules($chatId, false);
    }
}