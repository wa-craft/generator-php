<?php

namespace generator\node;

use generator\Cache;
use generator\driver\Driver;
use generator\helper\FileHelper;
use generator\helper\TemplateHelper;

/**
 * Class Project
 * @package generator\node
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
    //数据版本
    public $revision = '1';
    //应用列表
    public $applications = [];

    public function process()
    {
        $cache = Cache::getInstance();
        $config = $cache->get('config');
        $cache->set('company', $this->company);

        //生成 nginx 虚拟主机配置文件
        if ($config['actions']['nginx']) {
            Driver::load('profile\\Nginx', [
                    'path' => Cache::getInstance()->get('paths')['operation'],
                    'file_name' => 'nginx_vhost_orig',
                    'template' => TemplateHelper::fetchTemplate('nginx'),
                    'project' => $this->data,
                    'domain' => $this->data['domain']
                ])->execute()->writeToFile();

            if (isset($this->data['domain_test'])) {
                Driver::load('profile\\Nginx', [
                        'path' => Cache::getInstance()->get('paths')['profile'],
                        'file_name' => 'nginx_vhost_test',
                        'template' => TemplateHelper::fetchTemplate('nginx'),
                        'project' => $this->data,
                        'domain' => $this->data['domain_test']
                    ])->execute()->writeToFile();
            }
        }

        //生成 apache 虚拟主机配置文件
        if ($config['actions']['apache']) {
            Driver::load('profile\\Apache', [
                    'path' => Cache::getInstance()->get('paths')['profile'],
                    'file_name' => 'apache_vhost_orig',
                    'template' => TemplateHelper::fetchTemplate('apache'),
                    'project' => $this->data,
                    'domain' => $this->data['domain']
                ])->execute()->writeToFile();
            if (isset($this->data['domain_test'])) {
                Driver::load('profile\\Apache', [
                        'path' => Cache::getInstance()->get('paths')['profile'],
                        'file_name' => 'apache_vhost_test',
                        'template' => TemplateHelper::fetchTemplate('apache'),
                        'project' => $this->data,
                        'domain' => $this->data['domain_test']
                    ])->execute()->writeToFile();
            }
        }

        //生成 apache .htaccess 配置文件
        if ($config['actions']['apache_access']) {
            Driver::load('misc\\Apache', [
                'path' => Cache::getInstance()->get('paths')['public'],
                'file_name' => '.htaccess',
                'template' => TemplateHelper::fetchTemplate('apache_access'),
                'project' => $this->data
            ])->execute()->writeToFile();
        }

        //生成 6.0 的新目录结构
        $cache = Cache::getInstance();
        if ($config['actions']['copy']) {
            //拷贝应用文件
            FileHelper::copyFiles(RESOURCE_PATH . '/thinkphp/config', $cache->get('paths')['config']);
        }

        //处理app与database配置文件
        //写入应用 config 配置文件
        Driver::load('php\\AppConfig', [
            'path' => $cache->get('paths')['config'],
            'file_name' => 'app.php',
            'template' => TemplateHelper::fetchTemplate('config'),
            'data' => $this->data
        ])->execute()->writeToFile();

        //写入数据库配置文件
        Driver::load('php\\DBConfig', [
            'path' => $cache->get('paths')['database'],
            'file_name' => 'database.php',
            'template' => TemplateHelper::fetchTemplate('database'),
            'data' => $this->data
        ])->execute()->writeToFile();


        //写入cookie配置文件
        /*Driver::load('php\\CookieConfig', [
            'path' => $cache->get('paths')['application'] . '/config',
            'file_name' => 'cookie.php',
            'template' => TemplateHelper::fetchTemplate('cookie'),
            'data' => $this->data
        ])->execute()->writeToFile();*/

        $this->processChildren('application');
    }

    public function setNameSpace()
    {
        $this->namespace = '';
    }
}
