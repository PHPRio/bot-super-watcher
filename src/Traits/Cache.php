<?php
namespace Admin\Traits;

use Telegram\Bot\Objects\User;

trait Cache
{
    /**
     * @var \Memcached
     */
    private $memcache;
    /**
     * @return \Memcached
     */
    private function getCache()
    {
        if ($this->memcache) {
            return $this->memcache;
        }
        $this->memcache = new \Memcached();
        $this->memcache->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
        $this->memcache->addServers(array_map(function($server) { return explode(':', $server, 2); }, explode(',', $_ENV['MEMCACHEDCLOUD_SERVERS'])));
        if(!empty($_ENV['MEMCACHEDCLOUD_USERNAME'])) {
            $this->memcache->setSaslAuthData($_ENV['MEMCACHEDCLOUD_USERNAME'], $_ENV['MEMCACHEDCLOUD_PASSWORD']);
        }
        return $this->memcache;
    }

    public function getMe(): User
    {
        $me = $this->getCache()->get('me');
        if (!$me) {
            $me = $this->getTelegram()->getMe();
            $this->getCache()->set('me', $me);
        }
        return $me;
    }
}