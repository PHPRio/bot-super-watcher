<?php
namespace Admin\Traits;

trait Cache
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $cacheDriver;
    /**
     * @return \Doctrine\Common\Cache\Cache
     */
    private function getCache()
    {
        if ($this->cacheDriver) {
            return $this->cacheDriver;
        }
        $mc = new \Memcached();
        $mc->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
        $mc->addServers(array_map(function($server) { return explode(':', $server, 2); }, explode(',', $_ENV['MEMCACHEDCLOUD_SERVERS'])));
        if(!empty($_ENV['MEMCACHEDCLOUD_USERNAME'])) {
            $mc->setSaslAuthData($_ENV['MEMCACHEDCLOUD_USERNAME'], $_ENV['MEMCACHEDCLOUD_PASSWORD']);
        }
        $this->cacheDriver = new \Doctrine\Common\Cache\MemcachedCache();
        $this->cacheDriver->setMemcached($mc);
        return $this->cacheDriver;
    }
}