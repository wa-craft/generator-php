<?php
namespace thinkbuilder\generator\html;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\ClassHelper;
use thinkbuilder\helper\TemplateHelper;
use thinkbuilder\node\Field;
use thinkbuilder\node\Node;

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

        //处理关系，将关系转化成字段
        $fields = [];
        foreach ($data['relations'] as $relation) {
            if ($relation['this_key'] != 'id') {
                $fields[] = Node::create('Field', [
                    'name' => $relation['this_key'],
                    'caption' => $relation['caption'],
                    'rule' => 'number',
                    'required' => true,
                    'is_unique' => false
                ]);
            }
        }

        if (isset($data['fields'])) $fields = array_merge($data['fields'], $fields);

        //处理模型的字段
        if ($this->params['action_name'] == 'index') {
            //索引方法
            $_tr = "\t\t\t\t\t\t\t\t\t" . '<th > ID</th >' . PHP_EOL;
            $_td = "\t\t\t\t\t\t\t\t\t\t" . '<td>{$it.id}</td>' . PHP_EOL;
            foreach ($fields as $field) {
                $_tr .= "\t\t\t\t\t\t\t\t\t<th>" . $field->caption . '</th>' . PHP_EOL;
                if ($field->rule !== 'boolean') {
                    if (preg_match('/_id$/', $field->name)) {
                        $name = lcfirst(ClassHelper::convertFromTableName(str_replace('_id', '', $field->name)));
                        $_td .= "\t\t\t\t\t\t\t\t\t\t" . '<td>{$it->' . $name . '->name ?? $it->' . $name . '->title ??  $it->' . $name . '->caption ?? \'未定义\'}</td>' . PHP_EOL;
                    } else {
                        $_td .= "\t\t\t\t\t\t\t\t\t\t" . '<td>{$it.' . $field->name . '}</td>' . PHP_EOL;
                    }
                } else {
                    $_td .= "\t\t\t\t\t\t\t\t\t\t" . '<td>{$it.' . $field->name . ' == \'1\' ? \'是\' : \'否\'}</td>' . PHP_EOL;

                }
            }
            $tags = [
                'TR_LOOP' => $_tr,
                'TD_LOOP' => $_td
            ];
            $this->content = TemplateHelper::parseTemplateTags($tags, $content);
        } else {
            foreach ($fields as $field) {
                //如果是由系统填充的字段，则不生成添加或修改方法的代码
                if ($field->is_auto ?? false) continue;

                //判断校验规则
                if (isset($field->rule)) {
                    //判断是否是需要生成选择列表的外键
                    if (preg_match('/_id$/', $field->name)) {
                        $_comment = '请选择';
                    } else {
                        $_comment = '请输入';
                    }
                    $_comment .= $field->caption;
                } else {
                    $_comment = '';
                }

                if (isset($field->required)) $_is_required = ($field->required) ? '（* 必须）' : '';
                else $_is_required = '';
                $tags_field = [
                    'FORM_FIELD' => self::getFieldHTML($field, $this->params['action_name']),
                    'FIELD_NAME' => $field->name,
                    'FIELD_TITLE' => $field->caption,
                    'FIELD_COMMENT' => $_comment,
                    'IS_REQUIRED' => $_is_required
                ];
                $content = str_replace('{{FIELD_LOOP}}',
                    TemplateHelper::parseTemplateTags($tags_field, TemplateHelper::fetchTemplate('view_' . $this->params['action_name'] . '_field')) . "\n{{FIELD_LOOP}}", $content);
            }
            $this->content = str_replace("\n{{FIELD_LOOP}}", '', $content);
        }
        $this->content = str_replace('{{MODULE_NAME}}', $data['name'], $this->content);
        return $this;
    }


    /**
     * 判断字段的类型和参数，来生成不同类型的参数字段 html
     * @param $field
     * @param string $action
     * @return string
     */
    public static function getFieldHTML($field, $action = 'add')
    {
        if ($field->rule == 'boolean' || $field->rule == 'accepted') {
            switch ($action) {
                case 'add':
                    return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
                    break;
                case 'view':
                    return "{\$it.{{FIELD_NAME}}}";
                    break;
                case 'mod':
                default:
                    return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
            }
        }

        if ($field->rule == 'email') {
            switch ($action) {
                case 'add':
                    return "<span class=\"input-group-addon\"><i class=\"fa fa-envelope\"></i></span>"
                        . "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">"
                        . "<div class=\"form-control-focus\"></div>";
                    break;
                case 'view':
                    return "{\$it.{{FIELD_NAME}}}";
                    break;
                case 'mod':
                default:
                    return "<span class=\"input-group-addon\"><i class=\"fa fa-envelope\"></i></span>"
                        . "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">"
                        . "<div class=\"form-control-focus\"></div>";
            }
        }

        if ($field->rule == 'text') {
            switch ($action) {
                case 'add':
                    return "<textarea rows=\"4\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\"></textarea><div class=\"form-control-focus\"></div>";
                    break;
                case 'view':
                    return "{\$it.{{FIELD_NAME}}}";
                    break;
                case 'mod':
                default:
                    return "<textarea rows=\"4\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{\$it.{{FIELD_NAME}}}</textarea>"
                        . "<div class=\"form-control-focus\"></div>";
            }
        }

        if ($field->rule == 'datetime') {
            switch ($action) {
                case 'add':
                    return "<input id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" type=\"text\" class=\"form-control\" viewonly>"
                        . "<span class=\"input-group-btn\"><button class=\"btn default\" type=\"button\">"
                        . "<i class=\"fa fa-calendar\"></i></button></span>"
                        . "<div class=\"form-control-focus\"></div>";
                    break;
                case 'view':
                    return "{\$it.{{FIELD_NAME}}}";
                    break;
                case 'mod':
                default:
                    return "<input id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" type=\"text\" class=\"form-control\" value=\"{\$it.{{FIELD_NAME}}}\" viewonly>"
                        . "<span class=\"input-group-btn\"><button class=\"btn default\" type=\"button\">"
                        . "<i class=\"fa fa-calendar\"></i></button></span>"
                        . "<div class=\"form-control-focus\"></div>";
            }
        }

        if ($field->rule == 'image') {
            switch ($action) {
                case 'add':
                    return "<span class=\"input-group-addon\"><i class=\"fa fa-image\"></i></span>"
                        . "<input type=\"file\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">"
                        . "<div class=\"form-control-focus\"></div>";
                    break;
                case 'view':
                    return "{\$it.{{FIELD_NAME}}}";
                    break;
                case 'mod':
                default:
                    return "<span class=\"input-group-addon\"><i class=\"fa fa-image\"></i></span>" . PHP_EOL
                        . "\t\t\t\t\t{notempty name=\"it.{{FIELD_NAME}}\" value=\"\"}<img src=\"{\$it.{{FIELD_NAME}}}\" width='200px'>{/notempty}" . PHP_EOL
                        . "\t\t\t\t\t<input type=\"hidden\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">" . PHP_EOL
                        . "\t\t\t\t\t<input type=\"file\" class=\"md-check\" id=\"{{FIELD_NAME}}_new\" name=\"{{FIELD_NAME}}_new\"{notempty name=\"it.{{FIELD_NAME}}\" value=\"\"} value=\"{\$it.{{FIELD_NAME}}}\"{/notempty}>" . PHP_EOL
                        . "\t\t\t\t\t<div class=\"form-control-focus\"></div>";
            }
        }

        if (preg_match('/_id$/', $field->name)) {
            $_model = str_replace('_id', '', $field->name);
            switch ($action) {
                case 'add':
                    return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t{volist name=\"" . $_model . "List\" id=\"it2\"}" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t\t<option value=\"{\$it2.id}\">{\$it2.title ?? \$it2.caption ?? \$it2.name}</option>" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t{/volist}" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t</select>";
                    break;
                case 'view':
                    return "{\$it.{{FIELD_NAME}}}";
                    break;
                case 'mod':
                    return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t{volist name=\"" . $_model . "List\" id=\"it2\"}" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t\t<option value=\"{\$it2.id}\"{eq name=\"it2.id\" value=\"\$it.{{FIELD_NAME}}\"} selected{/eq}>{\$it2.title ?? \$it2.caption ?? \$it2.name}</option>" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t{/volist}" . PHP_EOL
                        . "\t\t\t\t\t\t\t\t</select>";
                default:
            }
        }

        switch ($action) {
            case 'add':
                return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
                break;
            case 'view':
                return "{\$it.{{FIELD_NAME}}}";
                break;
            case 'mod':
            default:
                return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
        }
    }
}