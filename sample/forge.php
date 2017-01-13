<?php
/**
 * 测试用实例
 * @author Bison 'goldeagle' Fan
 * @version 2.0
 * @update 20170113
 */
return [
    'name' => 'forge',
    'domain' => 'tf.vm',
    'domain_test' => 'tf.vm',
    'company' => '东莞培基',
    'vendor' => '东莞培基',
    'copyright' => '&copy;2017',
    'commands' => [
        [
            //执行的命令
            'command' => 'ls',
            //说明
            'comment' => '列表',
            //是否是生成代码之前运行，false 的话就是在生成代码之后运行
            'is_before' => true
        ]
    ],
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
                    'schemas' => [
                        [
                            'name' => 'Project',
                            'caption' => '项目',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'domain', 'caption' => '说明', 'rule' => 'domain', 'required' => true, 'default' => 'tf.vm', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'domain_test', 'caption' => '说明', 'rule' => 'domain', 'required' => true, 'default' => 'tf.vm', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'company', 'caption' => '公司名称', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'vendor', 'caption' => '供应商', 'rule' => 'chsDash', 'required' => true, 'default' => '东莞培基', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'copyright', 'caption' => '版权信息', 'rule' => 'chsDash', 'required' => true, 'default' => '&copy; 2017', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'is_external', 'caption' => '是否外部引入的与定义，不参与生成代码', 'rule' => 'boolean', 'required' => true, 'default' => false, 'is_unique' => false, 'is_auto' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'applications',
                                    'caption' => '模块',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'project_id',
                                    'model' => 'Application'
                                ],
                                [
                                    'name' => 'commands',
                                    'caption' => '命令',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'project_id',
                                    'model' => 'Command'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Command',
                            'caption' => '命令',
                            'fields' => [
                                ['name' => 'command', 'caption' => '命令', 'rule' => 'alphaDash', 'required' => true, 'default' => 'ls', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'comment', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'is_before', 'caption' => '是否是生成代码之前运行，false 的话就是在生成代码之后运行', 'rule' => 'boolean', 'required' => true, 'default' => true, 'is_unique' => false, 'is_auto' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'project',
                                    'caption' => '项目',
                                    'type' => 'belongsTo',
                                    'this_key' => 'project_id',
                                    'that_key' => 'id',
                                    'model' => 'Project'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Application',
                            'caption' => '应用',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'namespace', 'caption' => '应用的命名空间，小写', 'rule' => 'alpha', 'required' => true, 'default' => 'app', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'portal', 'caption' => '应用的入口文件，小写', 'rule' => 'alphaDash', 'required' => true, 'default' => 'index', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'auto_menu', 'caption' => '是否自动生成 menu 配置文件，可以不进行定义，默认为 true', 'rule' => 'boolean', 'required' => false, 'default' => true, 'is_unique' => false, 'is_auto' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'modules',
                                    'caption' => '模块',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'application_id',
                                    'model' => 'Module'
                                ],
                                [
                                    'name' => 'project',
                                    'caption' => '项目',
                                    'type' => 'belongsTo',
                                    'this_key' => 'project_id',
                                    'that_key' => 'id',
                                    'model' => 'Project'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Module',
                            'caption' => '模块',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'theme',
                                    'caption' => '主题',
                                    'type' => 'hasOne',
                                    'this_key' => 'theme_id',
                                    'that_key' => 'id',
                                    'model' => 'Theme'
                                ],
                                [
                                    'name' => 'controllers',
                                    'caption' => '控制器',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Class'
                                ],
                                [
                                    'name' => 'helpers',
                                    'caption' => '控制器',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Class'
                                ],
                                [
                                    'name' => 'traits',
                                    'caption' => '控制器',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Class'
                                ],
                                [
                                    'name' => 'models',
                                    'caption' => '模型',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Controller'
                                ],
                                [
                                    'name' => 'applications',
                                    'caption' => '应用',
                                    'type' => 'belongsTo',
                                    'this_key' => 'application_id',
                                    'that_key' => 'id',
                                    'model' => 'Application'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Theme',
                            'caption' => '主题',
                            'fields' => [
                                ['name' => 'module_id', 'caption' => '模块编号', 'required' => true, 'rule' => 'number'],
                                ['name' => 'name', 'caption' => '名称', 'required' => true, 'rule' => 'alpha'],
                                ['name' => 'annotation', 'caption' => '说明', 'required' => true, 'rule' => 'chsAlpha']
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
                            'name' => 'Class',
                            'caption' => '类定义',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'type',
                                    'caption' => '类的类型，包括：CRUD Controller|Plain Controller|Helper|Behavior|Trait',
                                    'type' => 'hasOne',
                                    'this_key' => 'type',
                                    'that_key' => 'id',
                                    'model' => 'Option'
                                ],
                                [
                                    'name' => 'parent_controller',
                                    'caption' => '方法',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'controller_id',
                                    'model' => 'Action'
                                ],
                                [
                                    'name' => 'traits',
                                    'caption' => '引用的泛型',
                                    'type' => 'belongsToMany',
                                    'this_key' => 'id',
                                    'that_key' => 'class_id',
                                    'model' => 'ClassTrait'
                                ],
                                [
                                    'name' => 'actions',
                                    'caption' => '方法',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'controller_id',
                                    'model' => 'Action'
                                ],
                                [
                                    'name' => 'module',
                                    'caption' => '模块',
                                    'type' => 'belongsTo',
                                    'this_key' => 'module_id',
                                    'that_key' => 'id',
                                    'model' => 'Module'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Action',
                            'caption' => '方法',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'is_abstract', 'caption' => '是否是抽象的', 'rule' => 'boolean', 'required' => true, 'default' => false, 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'is_static', 'caption' => '是否是静态的', 'rule' => 'boolean', 'required' => true, 'default' => false, 'is_unique' => false, 'is_auto' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'scope',
                                    'caption' => '作用域，包括 public|protected|private',
                                    'type' => 'hasOne',
                                    'this_key' => 'scope_id',
                                    'that_key' => 'id',
                                    'model' => 'Option'
                                ],
                                [
                                    'name' => 'params',
                                    'caption' => '字段',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'action_id',
                                    'model' => 'Parameter'
                                ],
                                [
                                    'name' => 'Class',
                                    'caption' => '所属对象',
                                    'type' => 'belongsTo',
                                    'this_key' => 'class_id',
                                    'that_key' => 'id',
                                    'model' => 'Class'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Model',
                            'caption' => '模型',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'autoWriteTimeStamp', 'caption' => '是否自动创建 create_time、update_time 模型属性', 'rule' => 'alphaDash', 'required' => true, 'default' => 'datetime', 'is_unique' => false, 'is_auto' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'profile',
                                    'caption' => '造型，包括：RowSet|KeyValue|Tree',
                                    'type' => 'hasOne',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Option'
                                ],
                                [
                                    'name' => 'fields',
                                    'caption' => '字段',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'module_id',
                                    'model' => 'Field'
                                ],
                                [
                                    'name' => 'relations',
                                    'caption' => '关联',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'model_id',
                                    'model' => 'Relation'
                                ],
                                [
                                    'name' => 'module',
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
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'rule', 'caption' => '取值约束，即校验规则，支持的校验规则请参考 thinkbuilder\node\Field::$rules', 'rule' => 'alphaDash', 'required' => true, 'default' => 'alpha', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'is_required', 'caption' => '是否为创建数据或更新数据时必须填充的内容', 'rule' => 'boolean', 'required' => true, 'default' => true, 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'is_unique', 'caption' => '表格中是否只允许唯一值', 'rule' => 'boolean', 'required' => true, 'default' => false, 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'is_auto', 'caption' => '是否为系统自动填充的字段，可以不进行定义，默认为 false', 'rule' => 'boolean', 'required' => true, 'default' => false, 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'default', 'caption' => '默认值', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
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
                            'name' => 'Trait',
                            'caption' => '泛型',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                            ],
                            'relations' => [
                                [
                                    'name' => 'actions',
                                    'caption' => '方法',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'class_id',
                                    'model' => 'Action'
                                ],
                                [
                                    'name' => 'classes',
                                    'caption' => '引用泛型的类',
                                    'type' => 'belongsToMany',
                                    'this_key' => 'id',
                                    'that_key' => 'trait_id',
                                    'model' => 'ClassTrait'
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
                            'name' => 'ClassTrait',
                            'caption' => '类与泛型映射关系',
                            'fields' => [
                                ['name' => 'name', 'caption' => '名称', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                            ],
                            'relations' => [
                                [
                                    'name' => 'class',
                                    'caption' => '类',
                                    'type' => 'belongsTo',
                                    'this_key' => 'class_id',
                                    'that_key' => 'id',
                                    'model' => 'Class'
                                ],
                                [
                                    'name' => 'trait',
                                    'caption' => '泛型',
                                    'type' => 'belongsTo',
                                    'this_key' => 'trait_id',
                                    'that_key' => 'id',
                                    'model' => 'Trait'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Relation',
                            'caption' => '设置',
                            'fields' => [
                                ['name' => 'setting', 'caption' => '设置', 'required' => true, 'rule' => 'alphaDash'],
                                ['name' => 'value', 'caption' => '取值', 'required' => true, 'rule' => 'chsAlpha']
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
                        ],
                        [
                            'name' => 'Option',
                            'caption' => '设置',
                            'fields' => [
                                ['name' => 'keyword', 'caption' => '键', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'value', 'caption' => '值', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                            ],
                            'relations' => [
                                [
                                    'name' => 'parent',
                                    'caption' => '父节点',
                                    'type' => 'hasOne',
                                    'this_key' => 'id',
                                    'that_key' => 'pid',
                                    'model' => 'Option'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Setting',
                            'caption' => '设置',
                            'fields' => [
                                ['name' => 'keyword', 'caption' => '键', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'caption', 'caption' => '说明', 'rule' => 'chsDash', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                                ['name' => 'value', 'caption' => '值', 'rule' => 'alpha', 'required' => true, 'default' => '', 'is_unique' => false, 'is_auto' => false],
                            ],
                            'relations' => [
                                [
                                    'name' => 'parent',
                                    'caption' => '父节点',
                                    'type' => 'hasOne',
                                    'this_key' => 'id',
                                    'that_key' => 'pid',
                                    'model' => 'Option'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
