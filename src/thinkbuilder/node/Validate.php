<?php
namespace thinkbuilder\node;

use thinkbuilder\Builder;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Validate
 * @package thinkbuilder\node
 */
class Validate extends Node
{
    //校验器下的规则
    protected $rules = [];

    public static function writeToFile($path, $module, $index, $model, $namespace, $templates)
    {
        $defaults = Builder::$defaults;
        FileHelper::mkdir($path);

        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $_class_name = $model['name'];
        $tags = [
            'NAME_SPACE' => $_namespace,
            'APP_PATH' => APP_PATH
        ];
        if (isset($module['comment'])) $tags['MODULE_COMMENT'] = $module['comment'];
        if (isset($module['comment'])) {
            $tags['APP_NAME'] = $model['name'];
            $tags['MODEL_NAME'] = $model['name'];
        }
        if (isset($model['comment'])) $tags['MODEL_COMMENT'] = $model['comment'];
        $content = TemplateHelper::parseTemplateTags($tags, TemplateHelper::fetchTemplate($index['name']));

        //处理校验器相关的模板
        $content_field = '';
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            foreach ($fields as $field) {
                $content_field .= PHP_EOL . "\t\t['" . $field['name'] . "', '";
                $content_field .= $field['required'] ? 'require|' : '';
                $content_field .= $field['rule'] . '\',\'';
                $content_field .= $field['required'] ? '必须输入：' . $field['title'] . '|' : '';
                $content_field .= Field::$rules[$field['rule']];
                $content_field .= '\'],';
            }
            $content = str_replace('{{VALIDATE_FIELDS}}', $content_field . "\n{{VALIDATE_FIELDS}}", $content);
        }
        $content = str_replace(",\n{{VALIDATE_FIELDS}}", "\n\t", $content);

        $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
        $_file = $path . '/' . $model['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}