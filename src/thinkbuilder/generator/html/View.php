<?php
namespace thinkbuilder\generator\html;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\ClassHelper;
use thinkbuilder\helper\TemplateHelper;
use thinkbuilder\node\Field;

class View extends Generator
{
    public function generate(): Generator
    {
        $data = $this->params['data'];

        $tags = [
            'MODEL_NAME' => ClassHelper::camelToDash($data['name']),
            'MODEL_COMMENT' => $data['caption'],
            'ACTION_COMMENT' => $this->params['action_caption'],
            'MODULE_COMMENT' => $data['module_caption'],
            'MODULE_NAME' => $data['module_name']
        ];
        $content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        //处理模型的字段
        if (isset($data['fields'])) {
            $fields = $data['fields'];
            if ($this->params['action_name'] == 'index') {
                //索引方法
                $_tr = "\t\t\t\t\t\t\t\t\t" . '<th > ID</th >' . PHP_EOL;
                $_td = "\t\t\t\t\t\t\t\t\t\t". '<td>{$it.id}</td>' . PHP_EOL;
                foreach ($fields as $field) {
                    $_tr .= "\t\t\t\t\t\t\t\t\t<th>" . $field->title . '</th>' . PHP_EOL;
                    $_td .= "\t\t\t\t\t\t\t\t\t\t".'<td>{$it.' . $field->name . '}</td>' . PHP_EOL;
                }
                $tags = [
                    'TR_LOOP' => $_tr,
                    'TD_LOOP' => $_td
                ];
                $this->content = TemplateHelper::parseTemplateTags($tags, $content);
            } else {
                foreach ($fields as $field) {
                    //如果是由系统填充的字段，则不生成添加或修改方法的代码
                    if($field->is_auto ?? false ) continue;

                    //判断校验规则
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
                        'FORM_FIELD' => self::getFieldHTML($field, $this->params['action_name']),
                        'FIELD_NAME' => $field->name,
                        'FIELD_TITLE' => $field->title,
                        'FIELD_COMMENT' => $_comment,
                        'IS_REQUIRED' => $_is_required
                    ];
                    $content = str_replace('{{FIELD_LOOP}}',
                        TemplateHelper::parseTemplateTags($tags_field, TemplateHelper::fetchTemplate('view_' . $this->params['action_name'] . '_field')) . "\n{{FIELD_LOOP}}", $content);
                }
                $this->content = str_replace("\n{{FIELD_LOOP}}", '', $content);
            }
        } else {
            $this->content = str_replace('{{MODULE_NAME}}', $data['name'], $content);
        }
        return $this;
    }


    /**
     * 判断字段的类型和参数，来生成不同类型的参数字段html
     * @param $field
     * @param string $action
     * @return string
     */
    public static function getFieldHTML($field, $action = 'add')
    {
        if ($field->rule == 'boolean') {
            if ($action == 'add') return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
            else  return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
        }

        if ($field->rule == 'email') {
            if ($action == 'add') return "<span class=\"input-group-addon\"><i class=\"fa fa-envelope\"></i></span><input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\"><div class=\"form-control-focus\"></div>";
            else  return "<span class=\"input-group-addon\"><i class=\"fa fa-envelope\"></i></span><input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\"><div class=\"form-control-focus\"></div>";
        }

        if ($field->rule == 'text') {
            if ($action == 'add') return "<textarea rows=\"4\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\"></textarea><div class=\"form-control-focus\"></div>";
            else  return "<textarea rows=\"4\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{\$it.{{FIELD_NAME}}}</textarea><div class=\"form-control-focus\"></div>";
        }

        if ($field->rule == 'datetime') {
            if ($action == 'add') return "<input id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" type=\"text\" class=\"form-control\" readonly><span class=\"input-group-btn\"><button class=\"btn default\" type=\"button\"><i class=\"fa fa-calendar\"></i></button></span><div class=\"form-control-focus\"></div>";
            else  return "<input id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" type=\"text\" class=\"form-control\" value=\"{\$it.{{FIELD_NAME}}}\" readonly><span class=\"input-group-btn\"><button class=\"btn default\" type=\"button\"><i class=\"fa fa-calendar\"></i></button></span><div class=\"form-control-focus\"></div>";
        }

        if ($field->rule == 'image') {
            if ($action == 'add') return "<span class=\"input-group-addon\"><i class=\"fa fa-image\"></i></span><input type=\"file\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\"><div class=\"form-control-focus\"></div>";
            else  return "<span class=\"input-group-addon\"><i class=\"fa fa-envelope\"></i></span><input type=\"file\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\"><div class=\"form-control-focus\"></div>";
        }

        if (preg_match('/_id$/', $field->name)) {
            $_model = str_replace('_id', '', $field->name);
            if ($action == 'add') return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{volist name=\"" . $_model . "List\" id=\"it\"}<option value=\"{\$it.id}\">{\$it.caption}</option>{/volist}</select> ";
            else return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{volist name=\"" . $_model . "List\" id=\"it\"}<option value=\"{\$it.id}\">{\$it.caption}</option>{/volist}</select> ";
        }

        if ($action == 'add') return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
        else  return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
    }
}