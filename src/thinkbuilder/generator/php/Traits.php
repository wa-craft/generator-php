<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class Traits 特性代码生成器
 * @package thinkbuilder\generator\php
 */
class Traits extends Generator
{
    public function generate(): Generator
    {
        $_class_name = $this->params['data']['name'];

        $traits = $this->params['data'];

        $content = str_replace('{{NAME_SPACE}}', $this->params['data']['namespace'], $this->params['template']);

        //处理特征的方法
        if (isset($traits['actions'])) {
            $actions = $traits['actions'];
            foreach ($actions as $action) {
                $content_action = TemplateHelper::fetchTemplate('traits_action');
                $content_action = str_replace('{{ACTION_NAME}}', $action['name'], $content_action);
                $content_action = str_replace('{{ACTION_CAPTION}}', $action['caption'], $content_action);
                if (array_key_exists('params', $action)) $content_action = str_replace('{{ACTION_PARAMS}}', $action['params'], $content_action);
                else  $content_action = str_replace('{{ACTION_PARAMS}}', '', $content_action);

                $content = str_replace('{{TRAITS_ACTIONS}}', $content_action . "\n{{TRAITS_ACTIONS}}", $content);
            }
        }
        $content = str_replace("{{TRAITS_ACTIONS}}", '', $content);

        $this->content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
        return $this;
    }
}