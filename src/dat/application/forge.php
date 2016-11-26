<?php
/**
 * 预定义的 forge 应用
 */
return [
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
                    'parent_controller' => 'TestController',
                    'actions' => [['name' => 'index', 'comment' => '默认方法']]
                ]
            ],
            'traits' => [
                [
                    'name' => 'Auth',
                    'comment' => '认证方法',
                    'actions' => [
                        ['name' => 'check', 'comment' => '校验', 'params' => '$username, $password']
                    ]
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
                    'comment' => '模块',
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
                    'comment' => '控制器',
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
                    'comment' => '模型',
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
                    'comment' => '字段',
                    'fields' => [
                        ['name' => 'model_id', 'title' => '模型编号', 'required' => true, 'rule' => 'number'],
                        ['name' => 'name', 'title' => '名称', 'required' => true, 'rule' => 'alpha'],
                        ['name' => 'annotation', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha'],
                        ['name' => 'is_required', 'title' => '是否必须', 'required' => true, 'rule' => 'boolean'],
                        ['name' => 'is_unique', 'title' => '是否唯一值', 'required' => true, 'rule' => 'boolean'],
                        ['name' => 'rule', 'title' => '校验规则', 'required' => true, 'rule' => 'alphaDash'],
                        ['name' => 'comment', 'title' => '说明', 'required' => true, 'rule' => 'chsAlpha'],
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
                    'comment' => '特征',
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
                    'comment' => '设置',
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
];
