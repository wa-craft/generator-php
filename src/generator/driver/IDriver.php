<?php

namespace generator\generator;

/**
 * Interface IDriver 生成器标准接口
 * @package generator\generator
 */
interface IDriver
{
    /**
     * 生成的方法
     * @return mixed
     */
    public function generate();
    public function writeToFile();
}
