<?php
namespace thinkbuilder\node;

/**
 * Class Module
 * @package thinkbuilder\node
 */
class Module extends Node
{
    //模块下的控制器
    protected $controllers = [];
    //模块下的模型
    protected $models = [];
    //模块下的校验器
    protected $validates = [];
    //模块下的视图
    protected $views = [];
    //默认模块下所有控制器的父控制器名称，会根据此名称自动生成默认控制器，并且模块下所有控制器继承自此控制器
    protected $default_controller = '';
}