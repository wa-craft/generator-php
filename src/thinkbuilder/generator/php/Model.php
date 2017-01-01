<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\ClassHelper;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class Model 模型代码生成器
 * @package thinkbuilder\generator\php
 */
class Model extends Generator
{
    public function generate(): Generator
    {
        $data = $this->params['data'];
        $tags = [
            'NAME_SPACE' => $data['namespace'],
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $data['name'],
            'AUTO_TIMESTAMP' => $data['autoWriteTimeStamp'] ? '"datetime"' : 'false'
        ];

        if (isset($module['caption'])) $tags['MODULE_COMMENT'] = $module['caption'];
        if (isset($module['caption'])) {
            $tags['APP_NAME'] = $data['name'];
            $tags['MODEL_NAME'] = $data['name'];
        }
        if (isset($data['caption'])) $tags['MODEL_COMMENT'] = $data['caption'];

        $content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        //生成 relations
        if (isset($data['relations'])) {
            $relations = $data['relations'];
            foreach ($relations as $relation) {
                $content_relation = TemplateHelper::parseTemplateTags(
                    [
                        'RELATION_NAME' => lcfirst($relation['name']),
                        'RELATION_TYPE' => $relation['type'] ?? 'hasOne',
                        'RELATION_MODEL' => $relation['model'] ?? $relation['name'],
                        'RELATION_THIS_KEY' => $relation['this_key'] ?? ClassHelper::convertToTableName($relation['model']).'_id',
                        'RELATION_THAT_KEY' => $relation['that_key'] ?? 'id',
                        'RELATION_CAPTION' => $relation['caption'] ?? ''
                    ],
                    TemplateHelper::fetchTemplate('model_relation')
                );
                $content = str_replace('{{RELATIONS}}', $content_relation . "\n{{RELATIONS}}", $content);
            }
            $this->content = str_replace("\n{{RELATIONS}}", '', $content);
        }

        return $this;
    }
}