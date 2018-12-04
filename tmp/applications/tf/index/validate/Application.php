<?php
namespace tf\index\validate;

/**
 * Class Application 应用校验器数据验证器
 * @package tf\index\validate
 */
class Application extends \think\Validate
{
    protected $rule = [
		'name' => ['require','alpha'],
		'caption' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'namespace' => ['require','alpha'],
		'portal' => ['require','alphaDash'],
		'auto_menu' => ['regex' => '/(yes|on|1|0)/i']
	];
    protected $field = [
		'name' => '名称',
		'caption' => '说明',
		'namespace' => '应用的命名空间，小写',
		'portal' => '应用的入口文件，小写',
		'auto_menu' => '是否自动生成 menu 配置文件，可以不进行定义，默认为 true'
	];
    protected $message = [
		'name' => '必须输入|英文字符',
		'caption' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'namespace' => '必须输入|英文字符',
		'portal' => '必须输入|英文字符与下划线',
		'auto_menu' => '是/否'
	];
}