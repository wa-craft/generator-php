<?php

declare(strict_types=1);

namespace generator;

use JetBrains\PhpStorm\NoReturn;

/**
 * Class Context 缓存类,使用单例模式
 * @package generator
 */
final class Context
{
    private static ?Context $instance = null;
    private array $context = [];

    /**
     * Context constructor. 私有化构造器，避免被构造
     */
    private function __construct()
    {
    }

    /**
     * 克隆会被触发错误
     */
    #[NoReturn] public function __clone()
    {
        trigger_error('Context must NOT be cloned!', E_USER_ERROR);
    }

    /**
     * 创建并返回实例
     * @return null|Context
     */
    public static function getInstance(): ?Context
    {
        if (self::$instance === null || !(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 通过 key 缓存对象
     * @param $key
     * @param $value
     */
    public function set($key, $value): void
    {
        $this->cache[$key] = $value;
    }

    /**
     * 通过 key 返回缓存对象
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key): mixed
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
    public function has($key): bool
    {
        return key_exists($key, $this->cache);
    }
}
