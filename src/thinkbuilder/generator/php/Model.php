<?php
/**
 * Created by PhpStorm.
 * User: bison
 * Date: 16-12-4
 * Time: 上午12:50
 */

namespace thinkbuilder\generator\php;


use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

class Model extends Generator
{
    public function generate(): Generator
    {
        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $tags = [
            'NAME_SPACE' => $_namespace,
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $model['name']
        ];

        if (isset($module['caption'])) $tags['MODULE_COMMENT'] = $module['caption'];
        if (isset($module['caption'])) {
            $tags['APP_NAME'] = $model['name'];
            $tags['MODEL_NAME'] = $model['name'];
        }
        if (isset($model['caption'])) $tags['MODEL_COMMENT'] = $model['caption'];

        $content = $content_relation = TemplateHelper::parseTemplateTags($tags, TemplateHelper::fetchTemplate($index['name']));

        //生成 relations
        if (isset($model['relations'])) {
            $relations = $model['relations'];
            foreach ($relations as $relation) {
                $content_relation = TemplateHelper::parseTemplateTags(
                    [
                        'RELATION_NAME' => $relation['name'],
                        'RELATION_TYPE' => $relation['type'],
                        'RELATION_MODEL' => $relation['model'],
                        'RELATION_THIS_KEY' => $relation['this_key'],
                        'RELATION_THAT_KEY' => $relation['that_key']
                    ],
                    TemplateHelper::fetchTemplate('model_relation')
                );
                $content = str_replace('{{RELATIONS}}', $content_relation . "\n{{RELATIONS}}", $content);
            }
            $this->content = str_replace("\n{{RELATIONS}}", '', $content);
        }

    }
}