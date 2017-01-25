<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;
use thinkbuilder\node\Field;

/**
 * Class Validate 校验器代码生成器
 * @package thinkbuilder\generator\php
 */
class Validate extends Generator
{
    public function generate(): Generator
    {
        $data = $this->params['data'];
        $tags = [
            'NAME_SPACE' => $data['namespace'],
            'APP_PATH' => APP_PATH
        ];
        $tags['MODEL_NAME'] = $data['name'];
        if (isset($data['caption'])) $tags['MODEL_COMMENT'] = $data['caption'];
        $content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        //处理校验器相关的模板
        $content_field = '';
        if (isset($data['fields'])) {
            $fields = $data['fields'];
            foreach ($fields as $field) {
                $content_field .= PHP_EOL . "\t\t['" . $field->name . "', '";
                $content_field .= $field->required ? 'require|' : '';
                switch ($field->rule) {
                    case 'image':
                        $rule = 'regex:\/[\.\w\\/]+\.jpg';
                        break;
                    case 'text':
                        $rule = 'min:3';
                        break;
                    default:
                        $rule = $field->rule;
                }
                $content_field .= $rule . '\',\'';
                $content_field .= $field->required ? '必须输入：' . $field->caption . '|' : '';
                $content_field .= Field::$rules[$field->rule];
                $content_field .= '\'],';
            }
            $content = str_replace('{{VALIDATE_FIELDS}}', $content_field . "\n{{VALIDATE_FIELDS}}", $content);
        }
        $content = str_replace(",\n{{VALIDATE_FIELDS}}", "\n\t", $content);

        $this->content = str_replace('{{CLASS_NAME}}', $data['name'], $content);

        return $this;
    }
}