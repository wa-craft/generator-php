<?php
namespace thinkbuilder\node;

/**
 * Class Field
 * @package thinkbuilder\node
 */
class Field extends Node
{
    //预定义的校验规则
    public static $rules = [
        'alpha' => '英文字符',
        'number' => '数字',
        'chsAlpha' => '中文或英文字符',
        'text' => '任何文字',
        'datetime' => '日期时间',
        'alphaDash' => '英文字符与下划线',
        'email' => '电子邮箱',
        'boolean' => '是/否',
        'url' => '合法的 uri 网址',
        'ip' => '合法的 ip 地址',
        'money' => '金额'
    ];

    //字段的校验规则
    protected $rule = '';
    //字段是否必须
    protected $is_required = false;
    //字段值必须唯一
    protected $is_unique = false;

    /**
     *
     * @param $field
     * @return string
     */
    public static function makeSQL($field)
    {
        if (preg_match('/_id$/', $field['name'])) {
            return '`{{FIELD_NAME}}` bigint(20) NOT NULL COMMENT \'{{FIELD_TITLE}}\',';
        }

        if (preg_match('/^is_/', $field['name'])) {
            return '`{{FIELD_NAME}}` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'{{FIELD_TITLE}}\',';
        }

        if ($field['rule'] == 'datetime') {
            return '`{{FIELD_NAME}}` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'{{FIELD_TITLE}}\',';
        }

        if ($field['rule'] == 'text') {
            return '`{{FIELD_NAME}}` TEXT COMMENT \'{{FIELD_TITLE}}\',';
        }

        return '`{{FIELD_NAME}}` varchar(100) NOT NULL COMMENT \'{{FIELD_TITLE}}\',';
    }
}