<?php
namespace thinkbuilder\node;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    ClassHelper, TemplateHelper, FileHelper
};

/**
 * Class View
 * @package thinkbuilder\node
 */
class View extends Node
{
    //视图使用的布局
    public $layout = '';
    public $actions = [];
    public $fields = [];

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);
        foreach ($this->data['actions'] as $action) {
            $template_name = isset($this->data['parent_controller']) ?
                ($this->data['parent_controller'] == '\\think\\Controller') ?
                    (TemplateHelper::hasTemplate('view_default_' . $action->name) ?
                        'view_' . $action->name :
                        'view_default') :
                    (TemplateHelper::hasTemplate('view_' . $action->name) ?
                        'view_' . $action->name :
                        'view_default')
                : (TemplateHelper::hasTemplate('view_' . $action->name) ?
                    'view_' . $action->name :
                    'view_default');

            Generator::create('html\\View', [
                'path' => $this->path . '/' . ClassHelper::convertToTableName($this->name),
                'file_name' => $action->name . '.html',
                'action_name' => $action->name,
                'action_caption' => $action->caption,
                'module_caption' => $this->data['module_caption'],
                'template' => TemplateHelper::fetchTemplate($template_name),
                'data' => $this->data
            ])->generate()->writeToFile();
        }
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\view';
        $this->data['namespace'] = $this->namespace;
    }
}