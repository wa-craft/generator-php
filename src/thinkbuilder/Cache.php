<?php
namespace thinkbuilder;


/**
 * Class Cache 缓存类,使用单例模式
 * @package thinkbuilder
 */
class Cache
{
    private static $instance = null;
    private $cache = [];

    /**
     * Cache constructor. 私有化构造器，避免被构造
     */
    private function __construct()
    {

    }

    /**
     * 克隆会被触发错误
     */
    public function __clone()
    {
        trigger_error('Cache must NOT be cloned!',E_USER_ERROR);
    }

    /**
     * 创建并返回实例
     * @return null|Cache
     */
    public static function getInstance()
    {
        if (self::$instance === null || !(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 通过数组设置所有缓存
     * @param array $cache
     */
    public function setCache(array $cache)
    {
        $this->cache = $cache;
    }

    /**
     * 返回所有缓存
     * @return array
     */
    public function getCache(): array
    {
        return $this->cache;
    }

    /**
     * 通过 key 缓存对象
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->cache[$key] = $value;
    }

    /**
     * 通过 key 返回缓存对象
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        $value = null;
        if (key_exists($key, $this->cache)) {
            $value = $this->cache[$key];
        }

        return $value;
    }

    /**
     * 判断 key 是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return key_exists($key, $this->cache);
    }
}