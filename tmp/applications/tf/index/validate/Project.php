<?php
namespace tf\index\validate;

/**
 * Class Project 项目校验器数据验证器
 * @package tf\index\validate
 */
class Project extends \think\Validate
{
    protected $rule = [
		'name' => ['require','alpha'],
		'caption' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'domain' => ['require','domain'],
		'domain_test' => ['require','domain'],
		'company' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'vendor' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'copyright' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'is_external' => ['require','regex' => '/(yes|on|1|0)/i']
	];
    protected $field = [
		'name' => '名称',
		'caption' => '说明',
		'domain' => '说明',
		'domain_test' => '说明',
		'company' => '公司名称',
		'vendor' => '供应商',
		'copyright' => '版权信息',
		'is_external' => '是否外部引入的与定义，不参与生成代码'
	];
    protected $message = [
		'name' => '必须输入|英文字符',
		'caption' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'domain' => '必须输入|合法的域名',
		'domain_test' => '必须输入|合法的域名',
		'company' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'vendor' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'copyright' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'is_external' => '必须输入|是/否'
	];
}