<?php

declare(strict_types=1);

namespace generator\parser;

use generator\Cache;
use generator\helper\FileHelper;
use generator\parser\component\Path;
use generator\parser\component\Schema;
use generator\task\GenerateCode;
use generator\task\TaskManager;
use generator\template\TemplateFactory;

/**
 * 从 component 定义文件生成 paths & schemas 数据的 parser
 */
class Openapi extends Parser
{
    public function parse(): void
    {
        //获取规则
        $cache = Cache::getInstance();
        $taskManager = TaskManager::getInstance();
        $resources = $cache->get('resources') ?: [];
        $paths = $cache->get('paths') ?: [];

        foreach ($resources as $resource) {
            $rules = $resource->getRules();
            $templates = $resource->getTemplates();

            foreach ($this->data_files as $f) {
                $data = FileHelper::readDataFromFile(ROOT_PATH . '/' . $f) ?: [];
                //处理 paths 数据
                $paths = $data['paths'] ?: [];
                foreach ($paths as $path_key => $path) {
                    $path_array = explode('/', $path_key);
                    if (is_array($path_array)) {
                        $params['action'] = array_pop($path_array);
                        $params['controller'] = array_pop($path_array);
                        $params['path'] = implode('/', $path_array);
                    }

                    $paths[] = new Path();
                }

                //处理 components 数据
                $components = $data['components'] ?: [];
                $this->processComponents($components, $taskManager, $rules, $templates);
            }
        }

        $cache->set('paths', $paths);
    }

    /**
     * @param $components
     * @param $taskManager
     * @param $rules
     * @param $templates
     * @return void
     */
    private function processComponents($components, $taskManager, $rules, $templates): void
    {
        $cache = Cache::getInstance();
        $cSchemas = $cache->get('schemas') ?: [];
        if (!empty($components)) {
                    $schemas = $components['schemas'] ?: [];
            foreach ($schemas as $schema_name => $schema) {
                //创建 schema 对象
                //读取rule/rules数据，遍历schema对象需要生成的模板名称
                $schema_targets = array_key_exists('schema', $rules) ? $rules['schema'] : [];
                foreach ($schema_targets as $target) {
                    foreach ($templates as $template) {
                        if (
                            array_key_exists('name', $template) &&
                            $template['name'] === $target
                        ) {
                            //处理schema核心代码
                            //使用schema数据生成openapi/Schema对象
                            $schema['name'] = $schema_name;
                            $oSchema = new Schema($schema);
                            $cSchemas[] = $oSchema;
                            //创建模板对象，并把openapi/Schema对象绑定到模板对象
                            $stereoType = TemplateFactory::create($template['stereotype'], ['schema' => $oSchema]);
                            //根据资源规则中对模板的定义，进行stereoType对象二次绑定

                            //使用模板对象创建GenerateCode任务对象，并将对象加入到对象管理器
                            $taskManager->addTask(new GenerateCode(['stereoType' => $stereoType, 'schema' => $schema]));
                        }
                    }
                }
            }
        }

        $cache->set('schemas', $cSchemas);
    }
}
