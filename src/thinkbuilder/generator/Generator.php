<?php
namespace thinkbuilder\generator;

use thinkbuilder\helper\FileHelper;

/**
 * Class AbstractGenerator 生成器类的接口适配器
 * @package thinkbuilder\generator
 */
abstract class Generator implements IGenerator
{
    //生成的文本内容
    protected $content = '';
    //用于设置的生成器参数
    protected $params = [
        //生成目标文件的路径
        'path' => '',
        //生成目标文件的相对文件名
        'file_name' => '',
        //生成目标文件的模板
        'template' => '',
        //项目数据
        'project' => []
    ];

    /**
     * 根据给出的类型，创建生成器的工厂方法
     * @param string $type
     * @param array $params
     * @return null|Generator
     */
    final public static function create($type = 'profile\\Nginx', $params = [])
    {
        $class = 'thinkbuilder\\generator\\' . $type;
        $obj = (class_exists($class)) ? new $class() : null;
        if ($obj instanceof Generator) $obj->setParams($params);
        return $obj;
    }

    /**
     * 生成的方法，需要在子类中实现
     * 返回对象实例，便于进行链式操作
     * @return Generator
     */
    abstract public function generate(): Generator;

    /**
     * 将内容写入到文件的方法
     */
    public function writeToFile()
    {
        $_file = $this->params['path'] . '/' . $this->params['file_name'];
        if ($this->content !== '') {
            FileHelper::mkdir($this->params['path']);
            echo "INFO: writing file: {$_file} ..." . PHP_EOL;
            file_put_contents($_file, $this->content);
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;
    }
}