<?php
namespace thinkbuilder\generator\sql;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    ClassHelper, TemplateHelper
};

class Model extends Generator
{
    public function generate(): Generator
    {
        $data = $this->params['data'];

        //处理SQL的字段
        $content = $this->params['template'];
        $content_field = '';
        if (isset($data['fields'])) {
            $fields = $data['fields'];
            foreach ($fields as $field) {
                $content_field = '  ' . TemplateHelper::getFieldSQL($field);
                $content_field = str_replace('{{FIELD_NAME}}', $field->name, $content_field);
                $content_field = str_replace('{{FIELD_TITLE}}', $field->title, $content_field);
                $content = str_replace('{{FIELD_LOOP}}', $content_field . "\n{{FIELD_LOOP}}", $content);
            }
        }

        $tags = [
            'NAME_SPACE' => $data['namespace'],
            'MODEL_NAME' => ClassHelper::convertToTableName($data['name'], $data['namespace']),
            'MODEL_COMMENT' => $data['caption'],
            'CONTROLLER_PARAMS' => $content_field,
            'CLASS_NAME' => $data['name'],
            'APP_PATH' => APP_PATH,
            'MODULE_NAME' => $data['namespace'],
            'FIELD_LOOP' => '',
        ];
        $this->content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        return $this;
    }
}