<?php
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

require_once 'vendor/autoload.php';

if(file_exists('.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
    $dotenv->required(['TELEGRAM_BOT_TOKEN', 'METADATA_FILE']);
}

if(getenv('MOCK_JSON')) {
    class mockApi extends Api{
        public function getWebhookUpdate($shouldEmitEvent = true) {
            $content = trim(getenv('MOCK_JSON'), "'");
            $content = '{"update_id":313197208,"message":{"message_id":80409,"from":{"id":606671062,"is_bot":false,"first_name":"\u254bVX,QQ\uff08\u540c\u53f7\uff09\uff1a253239090 \u4e13\u4e1a\u5de5\u4f5c\u5ba4\u63a8\u5e7f\u62c9\u4eba\u3010\u7535\u62a5\u7fa4\u62c9\u56fd\u5185\u5916\u6709\u65e0username\u90fd\u53ef\u62c9\u3001\u6307\u5b9a\u7fa4\u62c9\u4eba\u3011\u3010\u673a\u5668\u4eba\u5b9a\u5236\u3011\u3010\u793e\u7fa4\u4ee3\u8fd0\u8425\u3011\u3010twitter\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010facebook\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010youtube\u70b9\u8d5e\u3001\u8bc4\u8bba\u3011\u3010\u51fa\u552e\u6210\u54c1\u7535\u62a5\u8d26\u53f7\u3011 \uff08\u6b22\u8fce\u793e\u7fa4\u8fd0\u8425\u8005\u3001\u9879\u76ee\u65b9\u3001\u4ea4\u6613\u6240\u6d3d\u8c08\u5408\u4f5c\uff09\u4f18\u8d28\u7a7a\u6295\u5206\u4eabQQ\u7fa4473157472 \u672c\u5de5\u4f5c\u5ba4\u5168\u7f51\u6700\u4f4e\u4ef7\u3001\u670d\u52a1\u6700\u597d\u3001\u6d3b\u4eba\u8d28\u91cf\u6700\u9ad8 \u62db\u6536\u4ee3\u7406","last_name":"We can ADD 1000+ 10000+ or ANY NUMBER REAL and ACTIVE MEMBERS for your TELEGRAM GROUPS-LEAVE NO JOIN ALERTS,QUALITY and QUANTITY GUARANTEED,DEMO AVAILABLE.We also provide READY-MADE TELEGRAM ACCOUNTS and BROADCASTING SERVICE now you read.(To get our sevic","username":"iRnrATjjHRDq4"},"chat":{"id":-1001038741161,"title":"PHP Rio","username":"phprio","type":"supergroup"},"date":1532366641,"new_chat_participant":{"id":606671062,"is_bot":false,"first_name":"\u254bVX,QQ\uff08\u540c\u53f7\uff09\uff1a253239090 \u4e13\u4e1a\u5de5\u4f5c\u5ba4\u63a8\u5e7f\u62c9\u4eba\u3010\u7535\u62a5\u7fa4\u62c9\u56fd\u5185\u5916\u6709\u65e0username\u90fd\u53ef\u62c9\u3001\u6307\u5b9a\u7fa4\u62c9\u4eba\u3011\u3010\u673a\u5668\u4eba\u5b9a\u5236\u3011\u3010\u793e\u7fa4\u4ee3\u8fd0\u8425\u3011\u3010twitter\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010facebook\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010youtube\u70b9\u8d5e\u3001\u8bc4\u8bba\u3011\u3010\u51fa\u552e\u6210\u54c1\u7535\u62a5\u8d26\u53f7\u3011 \uff08\u6b22\u8fce\u793e\u7fa4\u8fd0\u8425\u8005\u3001\u9879\u76ee\u65b9\u3001\u4ea4\u6613\u6240\u6d3d\u8c08\u5408\u4f5c\uff09\u4f18\u8d28\u7a7a\u6295\u5206\u4eabQQ\u7fa4473157472 \u672c\u5de5\u4f5c\u5ba4\u5168\u7f51\u6700\u4f4e\u4ef7\u3001\u670d\u52a1\u6700\u597d\u3001\u6d3b\u4eba\u8d28\u91cf\u6700\u9ad8 \u62db\u6536\u4ee3\u7406","last_name":"We can ADD 1000+ 10000+ or ANY NUMBER REAL and ACTIVE MEMBERS for your TELEGRAM GROUPS-LEAVE NO JOIN ALERTS,QUALITY and QUANTITY GUARANTEED,DEMO AVAILABLE.We also provide READY-MADE TELEGRAM ACCOUNTS and BROADCASTING SERVICE now you read.(To get our sevic","username":"iRnrATjjHRDq4"},"new_chat_member":{"id":606671062,"is_bot":false,"first_name":"\u254bVX,QQ\uff08\u540c\u53f7\uff09\uff1a253239090 \u4e13\u4e1a\u5de5\u4f5c\u5ba4\u63a8\u5e7f\u62c9\u4eba\u3010\u7535\u62a5\u7fa4\u62c9\u56fd\u5185\u5916\u6709\u65e0username\u90fd\u53ef\u62c9\u3001\u6307\u5b9a\u7fa4\u62c9\u4eba\u3011\u3010\u673a\u5668\u4eba\u5b9a\u5236\u3011\u3010\u793e\u7fa4\u4ee3\u8fd0\u8425\u3011\u3010twitter\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010facebook\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010youtube\u70b9\u8d5e\u3001\u8bc4\u8bba\u3011\u3010\u51fa\u552e\u6210\u54c1\u7535\u62a5\u8d26\u53f7\u3011 \uff08\u6b22\u8fce\u793e\u7fa4\u8fd0\u8425\u8005\u3001\u9879\u76ee\u65b9\u3001\u4ea4\u6613\u6240\u6d3d\u8c08\u5408\u4f5c\uff09\u4f18\u8d28\u7a7a\u6295\u5206\u4eabQQ\u7fa4473157472 \u672c\u5de5\u4f5c\u5ba4\u5168\u7f51\u6700\u4f4e\u4ef7\u3001\u670d\u52a1\u6700\u597d\u3001\u6d3b\u4eba\u8d28\u91cf\u6700\u9ad8 \u62db\u6536\u4ee3\u7406","last_name":"We can ADD 1000+ 10000+ or ANY NUMBER REAL and ACTIVE MEMBERS for your TELEGRAM GROUPS-LEAVE NO JOIN ALERTS,QUALITY and QUANTITY GUARANTEED,DEMO AVAILABLE.We also provide READY-MADE TELEGRAM ACCOUNTS and BROADCASTING SERVICE now you read.(To get our sevic","username":"iRnrATjjHRDq4"},"new_chat_members":[{"id":606671062,"is_bot":false,"first_name":"\u254bVX,QQ\uff08\u540c\u53f7\uff09\uff1a253239090 \u4e13\u4e1a\u5de5\u4f5c\u5ba4\u63a8\u5e7f\u62c9\u4eba\u3010\u7535\u62a5\u7fa4\u62c9\u56fd\u5185\u5916\u6709\u65e0username\u90fd\u53ef\u62c9\u3001\u6307\u5b9a\u7fa4\u62c9\u4eba\u3011\u3010\u673a\u5668\u4eba\u5b9a\u5236\u3011\u3010\u793e\u7fa4\u4ee3\u8fd0\u8425\u3011\u3010twitter\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010facebook\u5173\u6ce8\u3001\u8f6c\u53d1\u3011\u3010youtube\u70b9\u8d5e\u3001\u8bc4\u8bba\u3011\u3010\u51fa\u552e\u6210\u54c1\u7535\u62a5\u8d26\u53f7\u3011 \uff08\u6b22\u8fce\u793e\u7fa4\u8fd0\u8425\u8005\u3001\u9879\u76ee\u65b9\u3001\u4ea4\u6613\u6240\u6d3d\u8c08\u5408\u4f5c\uff09\u4f18\u8d28\u7a7a\u6295\u5206\u4eabQQ\u7fa4473157472 \u672c\u5de5\u4f5c\u5ba4\u5168\u7f51\u6700\u4f4e\u4ef7\u3001\u670d\u52a1\u6700\u597d\u3001\u6d3b\u4eba\u8d28\u91cf\u6700\u9ad8 \u62db\u6536\u4ee3\u7406","last_name":"We can ADD 1000+ 10000+ or ANY NUMBER REAL and ACTIVE MEMBERS for your TELEGRAM GROUPS-LEAVE NO JOIN ALERTS,QUALITY and QUANTITY GUARANTEED,DEMO AVAILABLE.We also provide READY-MADE TELEGRAM ACCOUNTS and BROADCASTING SERVICE now you read.(To get our sevic","username":"iRnrATjjHRDq4"}]}}';
            return new Update(json_decode($content, true));
        }
    }
    $telegram = new mockApi();
} else {
    error_log(file_get_contents('php://input'));
    $telegram = new Api();
}

$update = $telegram->getWebhookUpdates();

if($update->has('message')){
    $message = $update->getMessage();
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
       $telegram->kickChatMember([
           'chat_id' => $update->getChat()->getId(),
           'user_id' => $isBanned->id
       ]);
       $telegram->deleteMessage([
           'chat_id' => $update->getChat()->getId(),
           'message_id' => $message->getMessageId()
       ]);
       if(getenv('ADMIN_GROUP')) {
           $telegram->sendMessage([
               'chat_id' => getenv('ADMIN_GROUP'),
               'text' => "BAN! Ban! Ban!!!\n".print_r($isBanned, true)
           ]);
       }
    }
    
}
