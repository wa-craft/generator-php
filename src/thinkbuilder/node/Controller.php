<?php
namespace thinkbuilder\node;

use thinkbuilder\Builder;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Controller
 * @package thinkbuilder\node
 */
class Controller extends Node
{
    //控制器下的方法
    protected $actions = [];
    //控制器默认的父控制器，如果设置此参数，则会忽略模块统一的默认父控制器设置
    protected $parent_controller = '';

    public static function writeToFile($path, $module, $index, $model, $namespace, $templates)
    {
        $defaults = Builder::$defaults;
        FileHelper::mkdir($path);

        $tags = [
            'NAME_SPACE' => $namespace . '\\' . $module['name'] . '\\' . $index['name'],
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $model['name']
        ];

        if (isset($module['comment'])) $tags['MODULE_COMMENT'] = $module['comment'];
        if (isset($model['name'])) {
            $tags['APP_NAME'] = $model['name'];
            $tags['MODEL_NAME'] = $model['name'];
        }
        if (isset($model['comment'])) {
            $tags['MODEL_COMMENT'] = $model['comment'];
        }

        //为控制器写入父控制器
        $extend_controller = (function ($module, $model, $defaults) {
            $controller = '';
            if (isset($model['parent_controller'])) {
                if ($model['parent_controller'] != '') {
                    $controller = $model['parent_controller'];
                }
            } else if (isset($module['default_controller'])) {
                if ($module['default_controller'] != '') {
                    $controller = $module['default_controller'];
                }
            }
            if ($controller == '') $controller = $defaults['controller'];
            return $controller;
        })($module, $model, $defaults);

        $tags['DEFAULT_CONTROLLER'] = $extend_controller;
        $content = TemplateHelper::parseTemplateTags($tags, TemplateHelper::fetchTemplate($index['name']));

        //处理与控制器相关的模板
        //处理控制器的方法
        if (isset($model['actions'])) {
            $actions = $model['actions'];
            foreach ($actions as $action) {
                $content_action = TemplateHelper::fetchTemplate('controller_action');
                $content_action = str_replace('{{ACTION_NAME}}', $action['name'], $content_action);
                $content_action = str_replace('{{ACTION_COMMENT}}', $action['comment'], $content_action);
                if (array_key_exists('params', $action)) $content_action = str_replace('{{ACTION_PARAMS}}', $action['params'], $content_action);
                else  $content_action = str_replace('{{ACTION_PARAMS}}', '', $content_action);

                $content = str_replace('{{CONTROLLER_ACTIONS}}', $content_action . "\n{{CONTROLLER_ACTIONS}}", $content);
            }
        }
        $tags['CONTROLLER_ACTIONS'] = '';

        //处理控制器的参数
        $content_field = '';
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            foreach ($fields as $field) {
                $content_field .= "\t\t\$model->" . $field['name'] . " = input('" . $field['name'] . "');\n";
            }
        }
        $tags['CONTROLLER_PARAMS'] = $content_field;

        $content = TemplateHelper::parseTemplateTags($tags, TemplateHelper::fetchTemplate($index['name']));

        $_file = $path . '/' . $model['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}