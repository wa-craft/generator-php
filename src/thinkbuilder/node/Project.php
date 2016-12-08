<?php
namespace thinkbuilder\node;

use thinkbuilder\Cache;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class Project
 * @package thinkbuilder\node
 */
class Project extends Node
{
    //项目使用的域名
    public $domain = '';
    public $applications = [];

    public function process()
    {
        $config = Cache::getInstance()->get('config');
        //生成 nginx 配置文件
        if ($config['actions']['nginx']) {
            Generator::create('profile\\Nginx', [
                    'path' => Cache::getInstance()->get('paths')['profile'],
                    'file_name' => 'nginx_vhost',
                    'template' => TemplateHelper::fetchTemplate('nginx'),
                    'project' => $this->data
                ]
            )->generate()->writeToFile();
        }

        //生成 apache htaccess 配置文件
        if ($config['actions']['apache']) {
            Generator::create('misc\\Apache', [
                'path' => Cache::getInstance()->get('paths')['public'],
                'file_name' => '.htaccess',
                'template' => TemplateHelper::fetchTemplate('apache'),
                'project' => $this->data
            ])->generate()->writeToFile();
        }

        $this->processChildren('application');
    }

    public function setNameSpace()
    {
        $this->namespace = '';
    }
}