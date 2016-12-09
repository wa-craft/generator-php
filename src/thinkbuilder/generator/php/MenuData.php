<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class MenuData 菜单数据生成器
 * @package thinkbuilder\generator\php
 */
class MenuData extends Generator
{
    public function generate(): Generator
    {
        $data = $this->params['data'];
        $items = "\t['title' => '{$data['name']}', 'url' => '', 'icon' => 'icon-folder', 'sub_menu' =>" . PHP_EOL;
        $items .= " \t\t[" . PHP_EOL;
        foreach ($data['modules'] as $module) {
            $items .= "\t\t\t['title' => '{$module['caption']}', 'url' => '', 'icon' => 'icon-folder', 'sub_menu' =>" . PHP_EOL;
            $items .= " \t\t\t\t[" . PHP_EOL;
            //处理 model，并创建自动控制器
            $controllers = [];
            if(isset($module['schemas'])) {
                foreach ($module['schemas'] as $model) {
                    $controllers[] = ['name' => $model['name'], 'caption' => $model['caption']];
                }

                //根据 controller 来生成菜单索引
                foreach ($controllers as $controller) {
                    $items .= "\t\t\t\t\t['title' => '{$controller['caption']}', 'url' => '{$module['name']}/{$controller['name']}/index', 'icon' => 'icon-folder']," . PHP_EOL;
                }
            }
            $items .= "\t\t\t\t]" . PHP_EOL;
            $items .= "\t\t\t]," . PHP_EOL;
        }
        $items .= "\t\t]" . PHP_EOL;
        $items .= "\t]";
        $this->content = TemplateHelper::parseTemplateTags(['ITEMS' => $items], $this->params['template']);
        return $this;
    }
}