<?php
class Traits extends Node
{
    //特征下的方法
    protected $actions = [];

    public static function writeToFile($path, $module, $index, $traits, $namespace, $templates)
    {
        FileHelper::mkdir($path);

        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $_class_name = $traits['name'];
        $content = str_replace('{{NAME_SPACE}}', $_namespace, Template::fetchTemplate($index['name']));

        //处理特征的方法
        if (isset($traits['actions'])) {
            $actions = $traits['actions'];
            foreach ($actions as $action) {
                $content_action = Template::fetchTemplate('traits_action');
                $content_action = str_replace('{{ACTION_NAME}}', $action['name'], $content_action);
                $content_action = str_replace('{{ACTION_COMMENT}}', $action['comment'], $content_action);
                if (array_key_exists('params', $action)) $content_action = str_replace('{{ACTION_PARAMS}}', $action['params'], $content_action);
                else  $content_action = str_replace('{{ACTION_PARAMS}}', '', $content_action);

                $content = str_replace('{{TRAITS_ACTIONS}}', $content_action . "\n{{TRAITS_ACTIONS}}", $content);
            }
        }
        $content = str_replace("{{TRAITS_ACTIONS}}", '', $content);

        $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
        $_file = $path . '/' . $traits['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}