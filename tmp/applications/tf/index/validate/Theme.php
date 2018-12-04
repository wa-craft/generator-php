<?php
namespace tf\index\validate;

/**
 * Class Theme 主题校验器数据验证器
 * @package tf\index\validate
 */
class Theme extends \think\Validate
{
    protected $rule = [
		'module_id' => ['require','number'],
		'name' => ['require','alpha'],
		'annotation' => ['require','chsAlpha']
	];
    protected $field = [
		'module_id' => '模块编号',
		'name' => '名称',
		'annotation' => '说明'
	];
    protected $message = [
		'module_id' => '必须输入|数字',
		'name' => '必须输入|英文字符',
		'annotation' => '必须输入|中文或英文字符'
	];
}