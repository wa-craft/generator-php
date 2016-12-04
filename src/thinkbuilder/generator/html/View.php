<?php
namespace thinkbuilder\generator\html;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;
use thinkbuilder\node\Field;

class View extends Generator
{
    public function generate(): Generator
    {
        //判断是否是独立控制器
        $content = str_replace('{{MODEL_NAME}}', strtolower($this->params['data']['name']), $this->params['template']);
        $content = str_replace('{{MODEL_COMMENT}}', $this->params['data']['caption'], $content);
        $content = str_replace('{{ACTION_COMMENT}}', $this->params['data']['caption'], $content);

        //处理模型的字段
        if (isset($this->params['data']['fields'])) {
            $fields = $this->params['data']['fields'];
            if ($this->params['data']['name'] == 'index') {
                //索引方法
                $_tr = '<th>ID</th>' . PHP_EOL;
                $_td = '<td>{$it.id}</td>' . PHP_EOL;
                foreach ($fields as $field) {
                    $_tr .= '<th>' . $field['title'] . '</th>' . PHP_EOL;
                    $_td .= '<td>{$it.' . $field['name'] . '}</td>' . PHP_EOL;
                }
                $content = str_replace('{{TR_LOOP}}', $_tr, $content);
                $this->content = str_replace('{{TD_LOOP}}', $_td, $content);
            } else {
                foreach ($fields as $field) {
                    if (isset($field->rule)) {
                        //判断是否是需要生成选择列表的外键
                        if (preg_match('/_id$/', $field->name)) {
                            $_comment = '请选择';
                        } else {
                            $_comment = '请输入';
                        }
                        $_comment .= $field->title . '，必须是' . Field::$rules[$field->rule];
                    } else {
                        $_comment = '';
                    }

                    if (isset($field->required)) $_is_required = ($field->required) ? '（* 必须）' : '';
                    else $_is_required = '';

                    $tags_field = [
                        'FORM_FIELD' => TemplateHelper::getFieldHTML($field, $this->params['data']['name']),
                        'FIELD_NAME' => $field->name,
                        'FIELD_TITLE' => $field->title,
                        'FIELD_COMMENT' => $_comment,
                        'IS_REQUIRED' => $_is_required
                    ];

                    $content = str_replace('{{FIELD_LOOP}}', TemplateHelper::parseTemplateTags($tags_field, TemplateHelper::fetchTemplate('view_' . $this->params['data']['name'] . '_field')) . "\n{{FIELD_LOOP}}", $content);
                }
                $this->content = str_replace("\n{{FIELD_LOOP}}", '', $content);
            }
        }
        return $this;
    }
}