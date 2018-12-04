<?php
namespace tf\index\validate;

/**
 * Class Relation 设置校验器数据验证器
 * @package tf\index\validate
 */
class Relation extends \think\Validate
{
    protected $rule = [
		'setting' => ['require','alphaDash'],
		'value' => ['require','chsAlpha']
	];
    protected $field = [
		'setting' => '设置',
		'value' => '取值'
	];
    protected $message = [
		'setting' => '必须输入|英文字符与下划线',
		'value' => '必须输入|中文或英文字符'
	];
}