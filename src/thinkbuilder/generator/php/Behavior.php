<?php
/**
 * Created by PhpStorm.
 * User: bison
 * Date: 16-12-9
 * Time: 下午4:43
 */

namespace thinkbuilder\generator\php;

use thinkbuilder\Cache;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class Behavior 行为生成器
 * @package thinkbuilder\generator\php
 */
class Behavior extends Generator
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
        $extend_controller = (function ($data) {
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
        $extend_controller = ($extend_controller !== '') ? 'extends ' . $extend_controller : '';
        $tags['EXTEND_CLASS'] = $extend_controller;
        $content = $this->params['template'];

        //处理与控制器相关的模板
        //处理控制器的方法
        if (isset($data['actions'])) {
            $actions = $data['actions'];
            foreach ($actions as $action) {
                $action_tags = [
                    'ACTION_NAME' => $action['name'],
                    'ACTION_COMMENT' => $action['caption']
                ];
                //如果 static 属性设置且为真，返回 static 字符串，否则返回空值
                $action_tags['IS_STATIC'] = ($action['static']??false) ? 'static ' : '';

                if (array_key_exists('params', $action)) $action_tags['ACTION_PARAMS'] = $action['params'];
                else  $action_tags['ACTION_PARAMS'] = '';
                $content_action = TemplateHelper::parseTemplateTags($action_tags, TemplateHelper::fetchTemplate('class_function'));

                $content = str_replace('{{CLASS_ACTIONS}}', $content_action . "\n{{CLASS_ACTIONS}}", $content);
            }
            $tags['CLASS_ACTIONS'] = '';
        }

        //处理控制器的参数
        $content_field = '';
        if (isset($data['fields'])) {
            $fields = $data['fields'];
            foreach ($fields as $field) {
                $content_field .= "\t\t\$model->" . $field['name'] . " = input('" . $field['name'] . "');\n";
            }
        }
        $tags['CONTROLLER_PARAMS'] = $content_field;

        $this->content = TemplateHelper::parseTemplateTags($tags, $content);

        return $this;
    }
}