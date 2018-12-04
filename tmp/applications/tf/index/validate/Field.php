<?php
namespace tf\index\validate;

/**
 * Class Field 字段校验器数据验证器
 * @package tf\index\validate
 */
class Field extends \think\Validate
{
    protected $rule = [
		'name' => ['require','alpha'],
		'caption' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'rule' => ['require','alphaDash'],
		'is_required' => ['require','regex' => '/(yes|on|1|0)/i'],
		'is_unique' => ['require','regex' => '/(yes|on|1|0)/i'],
		'is_auto' => ['require','regex' => '/(yes|on|1|0)/i'],
		'default' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u']
	];
    protected $field = [
		'name' => '名称',
		'caption' => '说明',
		'rule' => '取值约束，即校验规则，支持的校验规则请参考 thinkbuilder\node\Field::$rules',
		'is_required' => '是否为创建数据或更新数据时必须填充的内容',
		'is_unique' => '表格中是否只允许唯一值',
		'is_auto' => '是否为系统自动填充的字段，可以不进行定义，默认为 false',
		'default' => '默认值'
	];
    protected $message = [
		'name' => '必须输入|英文字符',
		'caption' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'rule' => '必须输入|英文字符与下划线',
		'is_required' => '必须输入|是/否',
		'is_unique' => '必须输入|是/否',
		'is_auto' => '必须输入|是/否',
		'default' => '必须输入|中文、中文标点或英文字符包括横线下划线'
	];
}