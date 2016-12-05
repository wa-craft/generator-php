<?php
/**
 * 测试用实例
 */
return [
    'name' => 'forge',
    'domain' => 'tf.vm',
    'applications' => [
        [
            'name' => 'forge',
            'namespace' => 'tf',
            'portal' => 'index',
            'caption' => 'ThinkForge',
            'modules' => [
                [
                    'name' => 'index',
                    'caption' => '默认模块',
                    'default_parent_controller' => '\\goldeagle\\thinklib\\controller\\DefaultController',
                    'controllers' => [
                        [
                            'name' => 'Index',
                            'caption' => '默认控制器',
                            'parent_controller' => '\\goldeagle\\thinklib\\controller\\DefaultController',
                            'actions' => [['name' => 'index', 'caption' => '默认方法']]
                        ]
                    ],
                    'traitss' => [
                        [
                            'name' => 'Auth',
                            'caption' => '认证方法',
                            'actions' => [
                                ['name' => 'check', 'caption' => '校验', 'params' => '$username, $password']
                            ]
                        ]
                    ],
                    'models' => [
                        [
                            'name' => 'Application',
                            'caption' => '应用',
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true],
                                ['name' => 'annotation', 'title' => '说明', 'rule' => 'alpha', 'required' => true],
                                ['name' => 'portal_file', 'title' => '入口文件', 'rule' => 'alphaDash', 'required' => true],
                                ['name' => 'logo', 'title' => '应用的logo', 'rule' => 'image', 'required' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'Modules',
                                    'caption' => '模块',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'application_id',
                                    'model' => 'Module'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Module',
                            'caption' => '模块',
                            'fields' => [
                                ['name' => 'application_id', 'title' => '应用编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ],
                            'relations' => [
                                [
                                    'name' => 'Controller',
                                    'caption' => '控制器',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Controller'
                                ],
                                [
                                    'name' => 'Model',
                                    'caption' => '模型',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Controller'
                                ],
                                [
                                    'name' => 'Application',
                                    'caption' => '应用',
                                    'type' => 'belongsTo',
                                    'this_key' => 'application_id',
                                    'that_key' => 'id',
                                    'model' => 'Application'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Controller',
                            'caption' => '控制器',
                            'fields' => [
                                ['name' => 'module_id', 'title' => '模块编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ],
                            'relations' => [
                                [
                                    'name' => 'Actions',
                                    'caption' => '方法',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'controller_id',
                                    'model' => 'ControllerAction'
                                ],
                                [
                                    'name' => 'Module',
                                    'caption' => '模块',
                                    'type' => 'belongsTo',
                                    'this_key' => 'module_id',
                                    'that_key' => 'id',
                                    'model' => 'Module'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Model',
                            'caption' => '模型',
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ],
                            'relations' => [
                                [
                                    'name' => 'Fields',
                                    'caption' => '字段',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Field'
                                ],
                                [
                                    'name' => 'Module',
                                    'caption' => '模块',
                                    'type' => 'belongsTo',
                                    'this_key' => 'module_id',
                                    'that_key' => 'id',
                                    'model' => 'Module'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Field',
                            'caption' => '字段',
                            'fields' => [
                                ['name' => 'model_id', 'title' => '模型编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha'],
                                ['name' => 'is_required', 'title' => '是否必须', 'required' => true, 'rule' => 'boolean'],
                                ['name' => 'is_unique', 'title' => '是否唯一值', 'required' => true, 'rule' => 'boolean'],
                                ['name' => 'rule', 'title' => '校验规则', 'required' => true, 'rule' => 'alphaDash'],
                                ['name' => 'caption', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha'],
                            ],
                            'relations' => [
                                [
                                    'name' => 'Model',
                                    'caption' => '模型',
                                    'type' => 'belongsTo',
                                    'this_key' => 'model_id',
                                    'that_key' => 'id',
                                    'model' => 'Model'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Traits',
                            'caption' => '特征',
                            'fields' => [
                                ['name' => 'module_id', 'title' => '模块编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha']
                            ],
                            'relations' => [
                                [
                                    'name' => 'Actions',
                                    'caption' => '方法',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'controller_id',
                                    'model' => 'TraitsAction'
                                ],
                                [
                                    'name' => 'Module',
                                    'caption' => '模块',
                                    'type' => 'belongsTo',
                                    'this_key' => 'module_id',
                                    'that_key' => 'id',
                                    'model' => 'Module'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Setting',
                            'caption' => '设置',
                            'fields' => [
                                ['name' => 'setting', 'title' => '设置', 'required' => true, 'rule' => 'alphaDash'],
                                ['name' => 'value', 'title' => '取值', 'required' => true, 'rule' => 'chsAlpha']
                            ],
                            'relations' => [
                                [
                                    'name' => 'Author',
                                    'caption' => '作者',
                                    'type' => 'hasOne',
                                    'this_key' => 'author_id',
                                    'that_key' => 'id',
                                    'model' => 'common/User'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
