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
        $content_rules = '';
        $content_fields = '';
        $content_messages = '';
        if (isset($data['fields'])) {
            $fields = $data['fields'];
            foreach ($fields as $field) {
                $content_rules .= PHP_EOL . "\t\t'" . $field->name . "' => ['";
                $content_fields .= PHP_EOL . "\t\t'" . $field->name . "' => '";
                $content_messages .= PHP_EOL . "\t\t'" . $field->name . "' => '";
                $content_rules .= $field->required ? "require','" : '';
                switch ($field->rule) {
                    case 'image':
                        $rule = "regex' => '/^((http)(s)?(:\/\/))?[\/a-zA-Z]{1}[0-9a-zA-Z\/\-\_\!\.]+[\.]{1}(jpg|jpeg|gif|png|bmp)$/i";
                        break;
                    case 'accepted':
                    case 'boolean':
                        $rule = "regex' => '/(yes|on|1|0)/i";
                        break;
                    case 'text':
                        $rule = 'min:3';
                        break;
                    case 'chs':
                        $rule = "regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u";
                        break;
                    case 'phone':
                        $rule = "regex' => '/^[0-9\+]{1}[0-9\-]{8,21}$/u";
                        break;
                    case 'mobile':
                        $rule = "regex' => '/^1[3|4|5|8][0-9]\d{4,8}$/";
                        break;
                    case 'password':
                        $rule = "regex' => '/^[A-Za-z0-9]{1}[\w\-#@!?$]{3,14}/";
                        break;
                    default:
                        $rule = $field->rule;
                }
                $content_rules .= $rule . '\'],';
                $content_fields .= $field->caption . '\',';;
                $content_messages .= $field->required ? '必须输入|' : '';
                $content_messages .= Field::$rules[$field->rule];
                $content_messages .= '\',';
            }

            //当 $fields 为空的时候，直接将校验器的 fields 和 rules 设置为空数组
            if (count($fields) === 0) {
                $content = str_replace('{{VALIDATE_RULES}}', $content_rules . "", $content);
                $content = str_replace('{{VALIDATE_FIELDS}}', $content_fields . "", $content);
                $content = str_replace('{{VALIDATE_MESSAGES}}', $content_messages . "", $content);
            } else {
                $content = str_replace('{{VALIDATE_RULES}}', $content_rules . "\n{{VALIDATE_RULES}}", $content);
                $content = str_replace('{{VALIDATE_FIELDS}}', $content_fields . "\n{{VALIDATE_FIELDS}}", $content);
                $content = str_replace('{{VALIDATE_MESSAGES}}', $content_messages . "\n{{VALIDATE_MESSAGES}}", $content);
            }
        }
        $content = str_replace(",\n{{VALIDATE_RULES}}", "\n\t", $content);
        $content = str_replace(",\n{{VALIDATE_FIELDS}}", "\n\t", $content);
        $content = str_replace(",\n{{VALIDATE_MESSAGES}}", "\n\t", $content);

        $this->content = str_replace('{{CLASS_NAME}}', $data['name'], $content);

        return $this;
    }
}