<?php
namespace tf\index\validate;

/**
 * Class Command 命令校验器数据验证器
 * @package tf\index\validate
 */
class Command extends \think\Validate
{
    protected $rule = [
		'command' => ['require','alphaDash'],
		'comment' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'is_before' => ['require','regex' => '/(yes|on|1|0)/i']
	];
    protected $field = [
		'command' => '命令',
		'comment' => '说明',
		'is_before' => '是否是生成代码之前运行，false 的话就是在生成代码之后运行'
	];
    protected $message = [
		'command' => '必须输入|英文字符与下划线',
		'comment' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'is_before' => '必须输入|是/否'
	];
}