<?php
namespace thinkbuilder\node;

/**
 * Class Project
 * @package thinkbuilder\node
 */
class Project extends Node
{
    //项目使用的域名
    protected $domain = '';
    //项目下的应用
    protected $applications = [];
}