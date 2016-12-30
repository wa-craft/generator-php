<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\Cache;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class Controller 控制器生成器
 * @package thinkbuilder\generator\php
 */
class Controller extends Generator
{
    public function generate(): Generator
    {
        $data = $this->params['data'];
        $tags = [
            'NAME_SPACE' => $data['namespace'],
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $data['name']
        ];

        if (isset($data['name'])) {
            $tags['APP_NAME'] = $data['name'];
            $tags['MODEL_NAME'] = $data['name'];
        }

        if (isset($data['caption'])) {
            $tags['CAPTION'] = $data['caption'];
        }

        //为控制器写入父控制器
        $extend_controller = (function () use ($data) {
            $controller = '';
            if (isset($data['parent_controller'])) {
                if ($data['parent_controller'] != '') {
                    $controller = $data['parent_controller'];
                }
            } else if (isset($data['default_controller'])) {
                if ($data['default_controller'] != '') {
                    $controller = $data['default_controller'];
                }
            }
            return $controller;
        })($data);
        $extend_controller = ($extend_controller !== '') ? 'extends ' . $extend_controller : (($data['name'] != 'Error') ? 'extends ' . $data['parent_controller'] : '');
        if ($extend_controller == 'extends ') $extend_controller = '\\think\\Controller';
        $tags['EXTEND_CLASS'] = $extend_controller;
        $content = $this->params['template'];

        //处理与控制器相关的模板
        //处理控制器的方法
        if (isset($data['actions'])) {
            $actions = $data['actions'];
            foreach ($actions as $action) {
                //当存在父控制器且为 index|add|mod 方法的时候，不生成方法代码
                $default_controller = 'extends ' . (($data['parent_controller']) ?? '');
                if ($extend_controller === $default_controller && $extend_controller !== 'extends \\think\\Controller' && in_array($action['name'], ['add', 'index', 'mod'])) {
                    continue;
                }
                $action_tags = [
                    'ACTION_NAME' => $action['name'],
                    'ACTION_COMMENT' => $action['caption']
                ];
                if (array_key_exists('params', $action)) $action_tags['ACTION_PARAMS'] = $action['params'];
                else  $action_tags['ACTION_PARAMS'] = '';
                $content_action = TemplateHelper::parseTemplateTags($action_tags, TemplateHelper::fetchTemplate('class_action'));

                $content = str_replace('{{CLASS_ACTIONS}}', $content_action . "\n{{CLASS_ACTIONS}}", $content);
            }
        }
        //如果设置了生成 menu 的参数，则系统创建构造器，并在构造器中注入 menu。
        $tags['CLASS_ACTIONS'] = Cache::getInstance()->get('autoMenu') && $data['name'] != 'Error' ?
            TemplateHelper::fetchTemplate('class_construct_action') : '';

        //处理控制器的参数
        $content_field = '';
        //处理关系，将关系转化成字段
        $fields = [];
        if (isset($data['relations'])) {
            foreach ($data['relations'] as $relation) {
                if ($relation['this_key'] != 'id') {
                    $fields[] = [
                        'name' => $relation['this_key'],
                        'title' => $relation['caption'],
                        'rule' => 'number',
                        'required' => true,
                        'is_unique' => false
                    ];
                }
            }
        }

        if (isset($data['fields'])) $fields = array_merge($data['fields'], $fields);
        foreach ($fields as $field) {
            $content_field .= "\t\t\$model->" . $field['name'] . " = input('" . $field['name'] . "');\n";
        }
        $tags['CONTROLLER_PARAMS'] = $content_field;

        $this->content = TemplateHelper::parseTemplateTags($tags, $content);

        return $this;
    }
}