<?php
namespace thinkbuilder\node;

use thinkbuilder\Builder;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class View
 * @package thinkbuilder\node
 */
class View extends Node
{
    //视图使用的布局
    protected $layout = '';

    public static function writeToFile($path, $module, $index, $model, $templates)
    {
        $defaults = Builder::$defaults;
        FileHelper::mkdir($path);

        //判断是否是独立控制器
        $content = str_replace('{{MODEL_NAME}}', strtolower($model['name']), TemplateHelper::fetchTemplate('view_' . $index['name']));
        $content = str_replace('{{MODULE_NAME}}', strtolower($module['name']), $content);
        $content = isset($module['comment']) ? str_replace('{{MODULE_COMMENT}}', $module['comment'], $content) : $content;
        $content = str_replace('{{MODEL_COMMENT}}', $model['comment'], $content);
        $content = str_replace('{{ACTION_COMMENT}}', $index['comment'], $content);

        //处理模型的字段
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            if ($index['name'] == 'index') {
                $_tr = '<th>ID</th>' . "\n";
                $_td = '<td>{$it.id}</td>' . "\n";
                foreach ($fields as $field) {
                    $_tr .= '<th>' . $field['title'] . '</th>' . "\n";
                    $_td .= '<td>{$it.' . $field['name'] . '}</td>' . "\n";
                }
                $content = str_replace('{{TR_LOOP}}', $_tr, $content);
                $content = str_replace('{{TD_LOOP}}', $_td, $content);
            } else {
                foreach ($fields as $field) {
                    if (isset($field['rule'])) {
                        //判断是否是需要生成选择列表的外键
                        if (preg_match('/_id$/', $field['name'])) {
                            $_comment = '请选择';
                        } else {
                            $_comment = '请输入';
                        }
                        $_comment .= $field['title'] . '，必须是' . $defaults['rules'][$field['rule']];
                    } else {
                        $_comment = '';
                    }

                    if (isset($field['required'])) $_is_required = ($field['required']) ? '（* 必须）' : '';
                    else $_is_required = '';

                    $tags_field = [
                        'FORM_FIELD' => TemplateHelper::getFieldHTML($field, $index['name']),
                        'FIELD_NAME' => $field['name'],
                        'FIELD_TITLE' => $field['title'],
                        'FIELD_COMMENT' => $_comment,
                        'IS_REQUIRED' => $_is_required
                    ];

                    $content = str_replace('{{FIELD_LOOP}}', TemplateHelper::parseTemplateTags($tags_field, TemplateHelper::fetchTemplate('view_' . $index['name'] . '_field')) . "\n{{FIELD_LOOP}}", $content);
                }
                $content = str_replace("\n{{FIELD_LOOP}}", '', $content);
            }
        }
        $_file = $path . '/' . strtolower($index['name']) . '.html';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}