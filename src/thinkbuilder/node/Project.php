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
    //项目使用的测试域名
    public $domain_test = '';
    //公司信息
    public $company = '';
    //版权信息
    public $copyright = '';
    //应用列表
    public $applications = [];

    public function process()
    {
        $cache = Cache::getInstance();
        $config = $cache->get('config');
        $cache->set('company', $this->company);

        //生成 nginx 虚拟主机配置文件
        if ($config['actions']['nginx']) {
            Generator::create('profile\\Nginx', [
                    'path' => Cache::getInstance()->get('paths')['profile'],
                    'file_name' => 'nginx_vhost_orig',
                    'template' => TemplateHelper::fetchTemplate('nginx'),
                    'project' => $this->data,
                    'domain' => $this->data['domain']
                ]
            )->generate()->writeToFile();

            if(isset($this->data['domain_test'])) {
                Generator::create('profile\\Nginx', [
                        'path' => Cache::getInstance()->get('paths')['profile'],
                        'file_name' => 'nginx_vhost_test',
                        'template' => TemplateHelper::fetchTemplate('nginx'),
                        'project' => $this->data,
                        'domain' => $this->data['domain_test']
                    ]
                )->generate()->writeToFile();
            }
        }

        //生成 apache 虚拟主机配置文件
        if ($config['actions']['apache']) {
            Generator::create('profile\\Apache', [
                    'path' => Cache::getInstance()->get('paths')['profile'],
                    'file_name' => 'apache_vhost_orig',
                    'template' => TemplateHelper::fetchTemplate('apache'),
                    'project' => $this->data,
                    'domain' => $this->data['domain']
                ]
            )->generate()->writeToFile();
            if(isset($this->data['domain_test'])) {
                Generator::create('profile\\Apache', [
                        'path' => Cache::getInstance()->get('paths')['profile'],
                        'file_name' => 'apache_vhost_test',
                        'template' => TemplateHelper::fetchTemplate('apache'),
                        'project' => $this->data,
                        'domain' => $this->data['domain_test']
                    ]
                )->generate()->writeToFile();
            }
        }

        //生成 apache .htaccess 配置文件
        if ($config['actions']['apache_access']) {
            Generator::create('misc\\Apache', [
                'path' => Cache::getInstance()->get('paths')['public'],
                'file_name' => '.htaccess',
                'template' => TemplateHelper::fetchTemplate('apache_access'),
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