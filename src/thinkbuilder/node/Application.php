<?php
namespace thinkbuilder\node;
use thinkbuilder\traits\CopyTrait;

/**
 * Class Application
 * @package thinkbuilder\node
 */
class Application extends Node
{
    use CopyTrait;

    //应用的根命名空间
    protected $namespace = '';
    //入口文件名称，不需要输入 .php 后缀
    protected $portal = '';
    //应用下的模块
    protected $modules = [];
}