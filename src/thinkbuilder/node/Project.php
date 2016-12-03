<?php
namespace thinkbuilder\node;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

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

    public function process()
    {
        //生成 nginx 配置文件
        if ($this->config['actions']['nginx']) {
            Generator::create('Profile', [
                    'type' => 'nginx',
                    'path' => $this->paths['profile'],
                    'file_name' => '/nginx_vhost',
                    'template' => TemplateHelper::fetchTemplate('nginx'),
                    'project' => $this->data
                ]
            )->generate()->writeToFile();
        }

        //生成 apache htaccess 配置文件
        if ($this->config['actions']['apache']) {
            Generator::create('Profile', [
                'type' => 'apache',
                'path' => $this->paths['public'],
                'file_name' => '/.htaccess',
                'template' => TemplateHelper::fetchTemplate('apache'),
                'project' => $this->data
            ])->generate()->writeToFile();
        }

        $this->processChildren('application');
    }
}