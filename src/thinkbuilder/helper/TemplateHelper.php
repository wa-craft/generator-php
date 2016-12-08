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
        'class' => '/php/class.tmpl',
        'class_action' => '/php/class_action.tmpl',
        'controller' => '/php/controller.tmpl',
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
        'nginx' => '/profile/nginx_vhost',
        'apache' => '/profile/apache_vhost',
        'apache_access' => '/misc/apache_access',
        'config' => '/php/config.tmpl',
        'database' => '/php/database.tmpl',
        'menu' => '/php/menu.tmpl'
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
}