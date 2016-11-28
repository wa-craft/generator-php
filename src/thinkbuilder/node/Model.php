<?php
namespace thinkbuilder\node;

use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Model
 * @package thinkbuilder\node
 */
class Model extends Node
{
    //模型下的字段
    protected $fields = [];
    //模型是否打开自动写入时间字段 create_time&update_time
    protected $autoWriteTimeStamp = false;
    //模型与其他模型的关系
    protected $relations = [];

    public static function writeToFile($path, $module, $index, $model, $namespace, $templates)
    {
        FileHelper::mkdir($path);

        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $tags = [
            'NAME_SPACE' => $_namespace,
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $model['name']
        ];

        if (isset($module['comment'])) $tags['MODULE_COMMENT'] = $module['comment'];
        if (isset($module['comment'])) {
            $tags['APP_NAME'] = $model['name'];
            $tags['MODEL_NAME'] = $model['name'];
        }
        if (isset($model['comment'])) $tags['MODEL_COMMENT'] = $model['comment'];

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
            $content = str_replace("\n{{RELATIONS}}", '', $content);
        }

        $_file = $path . '/' . $model['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}