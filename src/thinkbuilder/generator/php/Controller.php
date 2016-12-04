<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

class Controller extends Generator
{
    public function generate(): Generator
    {
        $tags = [
            'NAME_SPACE' => $this->params['data']['namespace'],
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $this->params['data']['name']
        ];

        if (isset($this->params['data']['name'])) {
            $tags['APP_NAME'] = $this->params['data']['name'];
            $tags['MODEL_NAME'] = $this->params['data']['name'];
        }

        if (isset($this->params['data']['caption'])) {
            $tags['CAPTION'] = $this->params['data']['caption'];
        }

        //为控制器写入父控制器
        $extend_controller = (function ($data) {
            $controller = '';
            if (isset($data['parent_controller'])) {
                if ($data['parent_controller'] != '') {
                    $controller = $this->params['data']['parent_controller'];
                }
            } else if (isset($this->params['data']['default_controller'])) {
                if ($this->params['data']['default_controller'] != '') {
                    $controller = $this->params['data']['default_controller'];
                }
            }
            return $controller;
        })($this->params['data']);

        if ($extend_controller !== '') $extend_controller = 'extends ' . $extend_controller;
        $tags['EXTEND_CONTROLLER'] = $extend_controller;

        $content = $this->params['template'];
        echo $extend_controller;
        //处理与控制器相关的模板
        //处理控制器的方法
        if (isset($this->params['data']['actions'])) {
            $actions = $this->params['data']['actions'];
            foreach ($actions as $action) {
                //当存在父控制器且为 index|add|mod 方法的时候，不生成方法代码
                if ($extend_controller !== '' && in_array($action['name'], ['add', 'index', 'mod'])) {
                    continue;
                }
                $content_action = TemplateHelper::fetchTemplate('controller_action');
                $content_action = str_replace('{{ACTION_NAME}}', $action['name'], $content_action);
                $content_action = str_replace('{{ACTION_COMMENT}}', $action['caption'], $content_action);
                if (array_key_exists('params', $action)) $content_action = str_replace('{{ACTION_PARAMS}}', $action['params'], $content_action);
                else  $content_action = str_replace('{{ACTION_PARAMS}}', '', $content_action);

                $content = str_replace('{{CONTROLLER_ACTIONS}}', $content_action . "\n{{CONTROLLER_ACTIONS}}", $content);
            }
        }
        $tags['CONTROLLER_ACTIONS'] = '';

        //处理控制器的参数
        $content_field = '';
        if (isset($this->params['data']['fields'])) {
            $fields = $this->params['data']['fields'];
            foreach ($fields as $field) {
                $content_field .= "\t\t\$model->" . $field['name'] . " = input('" . $field['name'] . "');\n";
            }
        }
        $tags['CONTROLLER_PARAMS'] = $content_field;

        $this->content = TemplateHelper::parseTemplateTags($tags, $content);

        return $this;
    }
}