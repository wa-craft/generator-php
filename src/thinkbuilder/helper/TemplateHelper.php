<?php
namespace thinkbuilder\helper;

use thinkbuilder\Cache;

/**
 * Class Template 模板管理类
 */
class TemplateHelper
{
    public static $templates = [
        'portal' => '/php/portal.tmpl',
        'class' => '/php/class.tmpl',
        'class_action' => '/php/class_action.tmpl',
        'class_function' => '/php/class_function.tmpl',
        'class_construct_action' => '/php/class_construct_action.tmpl',
        'error' => '/php/error.tmpl',
        'behavior' => '/php/behavior.tmpl',
        'controller' => '/php/controller.tmpl',
        'traits' => '/php/traits.tmpl',
        'traits_action' => '/php/traits_action.tmpl',
        'model' => '/php/model.tmpl',
        'model_relation' => '/php/model_relation.tmpl',
        'validate' => '/php/validate.tmpl',
        'view_add' => '/html/{{THEME}}/add.html',
        'view_view' => '/html/{{THEME}}/view.html',
        'view_index' => '/html/{{THEME}}/index.html',
        'view_controller_index' => '/html/{{THEME}}/controller_index.html',
        'view_mod' => '/html/{{THEME}}/mod.html',
        'view_add_field' => '/html/{{THEME}}/add_field.html',
        'view_view_field' => '/html/{{THEME}}/view_field.html',
        'view_index_field' => '/html/{{THEME}}/index_field.html',
        'view_mod_field' => '/html/{{THEME}}/mod_field.html',
        'view_default_login' => '/html/{{THEME}}/login.html',
        'view_default_register' => '/html/{{THEME}}/register.html',
        'view_default_logout' => '/html/{{THEME}}/logout.html',
        'view_default' => '/html/{{THEME}}/default.html',
        'view_layout_footer' => '/html/{{THEME}}/layout/footer.html',
        'view_layout_header' => '/html/{{THEME}}/layout/html_header.html',
        'sql_table' => '/sql/table.sql',
        'nginx' => '/profile/nginx_vhost',
        'apache' => '/profile/apache_vhost',
        'apache_access' => '/misc/apache_access',
        'config' => '/php/config.tmpl',
        'database' => '/php/database.tmpl',
        'menu' => '/php/menu.tmpl'
    ];

    /**
     * 获取模板文件中的内容，如果模板是html开头，则使用主题
     * @param string $template_name
     * @return string
     */
    public static function fetchTemplate(string $template_name): string
    {
        $content = '';

        if (key_exists($template_name, self::$templates)) {
            $file = self::$templates[$template_name];
            if (preg_match('/^view/', $template_name)) {
                $theme = Cache::getInstance()->get('theme') ?? Cache::getInstance()->get('config')['defaults']['theme'];
                $file = str_replace('{{THEME}}', $theme, self::$templates[$template_name]);
            }

            $content = file_get_contents(TMPL_PATH . $file);
        }

        return $content;
    }

    /**
     * 判断模板是否存在
     * @param string $template_name
     * @return bool
     */
    public static function hasTemplate(string $template_name): bool
    {
        return key_exists($template_name, self::$templates);
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