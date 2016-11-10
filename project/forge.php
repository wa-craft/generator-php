<?php
return [
    'name' => 'forge',
    'domain' => 'tf.vm',
    'applications' => [
        [
            'name' => 'forge',
            'namespace' => 'tf',
            'portal' => 'forge',
            'comment' => 'ThinkForge',
            'modules' => [
                [
                    'name' => 'index',
                    'comment' => '默认模块',
                    'controllers' => [
                        [
                            'name' => 'Index',
                            'comment' => '默认控制器',
                            'actions' => [['name' => 'index', 'comment' => '默认方法']]
                        ]
                    ],
                    'models' => [
                        [
                            'name' => 'Application',
                            'comment' => '应用',
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true],
                                ['name' => 'annotation', 'title' => '说明', 'rule' => 'alpha', 'required' => true],
                                ['name' => 'portal_file', 'title' => '入口文件', 'rule' => 'alphaDash', 'required' => true]
                            ]
                        ],
                        [
                            'name' => 'Module',
                            'comment' => '模块',
                            'fields' => [
                                ['name' => 'application_id', 'title' => '应用编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ]
                        ],
                        [
                            'name' => 'Controller',
                            'comment' => '控制器',
                            'fields' => [
                                ['name' => 'module_id', 'title' => '模块编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ]
                        ],
                        [
                            'name' => 'Model',
                            'comment' => '模型',
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ]
                        ],
                        [
                            'name' => 'Field',
                            'comment' => '字段',
                            'fields' => [
                                ['name' => 'model_id', 'title' => '模型编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha'],
                                ['name' => 'is_required', 'title' => '是否必须', 'required' => true, 'rule' => 'boolean'],
                                ['name' => 'is_unique', 'title' => '是否唯一值', 'required' => true, 'rule' => 'boolean'],
                                ['name' => 'rule', 'title' => '校验规则', 'required' => true, 'rule' => 'alphaDash'],
                                ['name' => 'comment', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha'],
                            ]
                        ],
                        [
                            'name' => 'Traits',
                            'comment' => '特征',
                            'fields' => [
                                ['name' => 'module_id', 'title' => '模块编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ]
                        ],
                        [
                            'name' => 'Setting',
                            'comment' => '设置',
                            'fields' => [
                                ['name' => 'setting', 'title' => '设置', 'required' => true, 'rule' => 'alphaDash'],
                                ['name' => 'value', 'title' => '取值', 'required' => true, 'rule' => 'chsAlpha']
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
