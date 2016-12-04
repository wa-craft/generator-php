<?php
namespace thinkbuilder\helper;

use thinkbuilder\node\Field;

/**
 * Class Template 模板管理类
 */
class TemplateHelper
{
    public static $templates = [
        'portal' => '/php/portal.tmpl',
        'controller' => '/php/controller.tmpl',
        'controller_action' => '/php/controller_action.tmpl',
        'traits' => '/php/traits.tmpl',
        'traits_action' => '/php/traits_action.tmpl',
        'model' => '/php/model.tmpl',
        'model_relation' => '/php/model_relation.tmpl',
        'validate' => '/php/validate.tmpl',
        'view_add' => '/html/add.html',
        'view_index' => '/html/index.html',
        'view_controller_index' => '/html/controller_index.html',
        'view_mod' => '/html/mod.html',
        'view_add_field' => '/html/add_field.html',
        'view_index_field' => '/html/index_field.html',
        'view_mod_field' => '/html/mod_field.html',
        'view_login' => '/html/login.html',
        'view_register' => '/html/register.html',
        'view_logout' => '/html/logout.html',
        'sql_table' => '/sql/table.sql',
        'nginx' => '/misc/nginx_vhost',
        'apache' => '/misc/apache_access',
        'config' => '/php/config.tmpl',
        'database' => '/php/database.tmpl'
    ];

    /**
     * 获取模板文件中的内容
     * @param string $template_name
     * @return string
     */
    public static function fetchTemplate(string $template_name)
    {
        $content = '';
        if (key_exists($template_name, self::$templates))
            $content = file_get_contents(TMPL_PATH . self::$templates[$template_name]);
        return $content;
    }

    /**
     * 通过数组来修改模板
     * @param array $map
     * @param $template
     * @return mixed
     */
    public static function parseTemplateTags($map = [], $template)
    {
        $_c = $template;
        foreach ($map as $key => $item) {
            $_c = str_replace('{{' . $key . '}}', $item, $_c);
        }
        return $_c;
    }

    /**
     * 生成 sql 代码
     * @param $path
     * @param $index
     * @param $model
     * @param $namespace
     * @param $templates
     */
    public static function write_sql($path, $index, $model, $namespace, $templates)
    {
        FileHelper::mkdir($path);

        $_class_name = $model['name'];
        $content = str_replace('{{APP_NAME}}', $namespace, TemplateHelper::fetchTemplate($index['name']));
        $content = isset($model['name']) ? str_replace('{{MODEL_NAME}}', ClassHelper::convertToTableName($model['name'], $namespace), $content) : $content;
        $content = isset($model['comment']) ? str_replace('{{MODEL_COMMENT}}', $model['comment'], $content) : $content;

        //处理SQL的字段
        $content_field = '';
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            foreach ($fields as $field) {
                $content_field = '  ' . TemplateHelper::getFieldSQL($field);
                $content_field = str_replace('{{FIELD_NAME}}', $field->name, $content_field);
                $content_field = str_replace('{{FIELD_TITLE}}', $field->title, $content_field);
                $content = str_replace('{{FIELD_LOOP}}', $content_field . "\n{{FIELD_LOOP}}", $content);
            }
            $content = str_replace('{{FIELD_LOOP}}', '', $content);
        }
        $content = str_replace('{{CONTROLLER_PARAMS}}', $content_field, $content);
        $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
        $content = str_replace('{{MODULE_NAME}}', $namespace, $content);

        $_file = $path . '/' . ClassHelper::convertToTableName($model['name'], $namespace) . '.sql';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }

    /**
     * 判断字段的类型和参数，来生成不同类型的参数字段html
     * @param $field
     * @param string $action
     * @return string
     */
    public static function getFieldHTML($field, $action = 'add')
    {
        if ($field->rule == 'boolean') {
            if ($action == 'add') return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
            else  return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
        }

        if ($field->rule == 'email') {
            if ($action == 'add') return "<span class=\"input-group-addon\"><i class=\"fa fa-envelope\"></i></span><input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\"><div class=\"form-control-focus\"></div>";
            else  return "<span class=\"input-group-addon\"><i class=\"fa fa-envelope\"></i></span><input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\"><div class=\"form-control-focus\"></div>";
        }

        if ($field->rule == 'text') {
            if ($action == 'add') return "<textarea rows=\"4\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\"></textarea><div class=\"form-control-focus\"></div>";
            else  return "<textarea rows=\"4\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{\$it.{{FIELD_NAME}}}</textarea><div class=\"form-control-focus\"></div>";
        }

        if ($field->rule == 'datetime') {
            if ($action == 'add') return "<input id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" type=\"text\" class=\"form-control\" readonly><span class=\"input-group-btn\"><button class=\"btn default\" type=\"button\"><i class=\"fa fa-calendar\"></i></button></span><div class=\"form-control-focus\"></div>";
            else  return "<input id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" type=\"text\" class=\"form-control\" value=\"{\$it.{{FIELD_NAME}}}\" readonly><span class=\"input-group-btn\"><button class=\"btn default\" type=\"button\"><i class=\"fa fa-calendar\"></i></button></span><div class=\"form-control-focus\"></div>";
        }

        if (preg_match('/_id$/', $field->name)) {
            $_model = str_replace('_id', '', $field->name);
            if ($action == 'add') return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{volist name=\"" . $_model . "List\" id=\"it\"}<option value=\"{\$it.id}\">{\$it.caption}</option>{/volist}</select> ";
            else return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{volist name=\"" . $_model . "List\" id=\"it\"}<option value=\"{\$it.id}\">{\$it.caption}</option>{/volist}</select> ";
        }
        if ($action == 'add') return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
        else  return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
    }

    /**
     * 判断字段的类型和参数，生成不同类型的字段 sql
     * @param $field
     * @return string
     */
    public static function getFieldSQL($field)
    {
        //字段是否必须
        $null_string = (isset($field->required)) ? ($field->required ? ' NOT NULL ' : '') : '';

        if (preg_match('/_id$/', $field->name)) {
            return "`{{FIELD_NAME}}` bigint(20) $null_string COMMENT '{{FIELD_TITLE}}',";
        }

        if (preg_match('/^is_/', $field->name)) {
            $default = (array_key_exists('default', $field)) ? ($field->default ? '\'1\'' : '\'0\'') : '\'0\'';
            return "`{{FIELD_NAME}}` tinyint(1) DEFAULT $default COMMENT '{{FIELD_TITLE}}',";
        }

        if ($field->rule == 'datetime') {
            return "`{{FIELD_NAME}}` datetime $null_string DEFAULT CURRENT_TIMESTAMP COMMENT '{{FIELD_TITLE}}',";
        }

        if ($field->rule == 'text') {
            return "`{{FIELD_NAME}}` TEXT COMMENT '{{FIELD_TITLE}}',";
        }

        $default = (array_key_exists('default', $field)) ? ' DEFAULT \'' . $field->default . '\' ' : ' DEFAULT NULL';
        $null_string = $default !== '' ? $default : $null_string;

        return "`{{FIELD_NAME}}` varchar(100) $null_string COMMENT '{{FIELD_TITLE}}',";
    }
}