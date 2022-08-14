<?php

namespace generator\node;

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
        'float' => '浮点数字',
        'chs' => '中英文字符',
        'chsAlpha' => '中文或英文字符',
        'chsDash' => '中文、中文标点或英文字符包括横线下划线',
        'text' => '任何文字',
        'date' => '日期',
        'datetime' => '日期时间',
        'alphaNum' => '英文字符与数字',
        'alphaDash' => '英文字符与下划线',
        'password' => '合法的密码，可以使用英文与数字的组合，还有-_@!#$符号等，4～15位长度',
        'phone' => '合法的电话号码，例如 +86-0769-88888888-8888',
        'mobile' => '合法的手机号码，例如 13000001234',
        'email' => '合法的电子邮箱，例如 xxx_123@xxx.com',
        'boolean' => '是/否',
        'accepted' => 'on|yes|1(是）或者 off|no|0（否）',
        'url' => '合法的 uri 网址',
        'domain' => '合法的域名',
        'ip' => '合法的 ip 地址，如 202.101.155.254',
        'money' => '金额，如1234.56',
        'image' => '图片'
    ];

    public $caption = '';

    //字段的校验规则
    public $rule = '';
    //字段是否必须
    public $required = false;
    public $default = '';
    //字段值必须唯一
    public $is_unique = false;
    //字段是否系统填充
    public $is_auto = false;

    public function process()
    {
    }

    public function setNameSpace()
    {
    }

    /**
     *
     * @param $field
     * @return string
     */
    public static function makeSQL($field)
    {
        //字段是否必须
        $null_string = (array_key_exists('required', $field)) ? ($field['required'] ? ' NOT NULL ' : '') : '';

        if (preg_match('/_id$/', $field['name'])) {
            return "`{{FIELD_NAME}}` bigint(20) $null_string COMMENT '{{FIELD_TITLE}}',";
        }

        if (preg_match('/^is_/', $field['name'])) {
            $default = (array_key_exists('default', $field)) ? ($field['default'] ? '\'1\'' : '\'0\'') : '\'0\'';
            return "{{FIELD_NAME}}` tinyint(1) $null_string DEFAULT ' . $default . ' COMMENT '{{FIELD_TITLE}}',";
        }

        if ($field['rule'] == 'datetime') {
            return "{{FIELD_NAME}}` datetime $null_string DEFAULT CURRENT_TIMESTAMP COMMENT '{{FIELD_TITLE}}',";
        }

        if ($field['rule'] == 'text') {
            return "{{FIELD_NAME}}` TEXT COMMENT '{{FIELD_TITLE}}',";
        }

        $default = (array_key_exists('default', $field)) ? ' DEFAULT \'' . $field['default'] . '\' ' : '';
        $null_string = $default !== '' ? $default : $null_string;

        return "{{FIELD_NAME}}` varchar(100) $null_string COMMENT '{{FIELD_TITLE}}',";
    }
}
