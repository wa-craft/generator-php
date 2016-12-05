<?php
namespace thinkbuilder\generator;


/**
 * Interface IGenerator 生成器标准接口
 * @package thinkbuilder\generator
 */
interface IGenerator
{
    /**
     * 生成的方法
     * @return mixed
     */
    public function generate();
    public function writeToFile();
}