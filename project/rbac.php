<?php
/**
 * RBAC样例
 */
return [
    'name' => 'rbac',
    'domain' => 'rbac.vm',
    'applications' => [
        [
            'name' => 'application',
            'namespace' => 'app',
            'portal' => 'index',
            'comment' => '',
            'modules' => [
                [
                    'name' => 'auth',
                    'comment' => '默认模块',
                    'default_controller' => '',
                    'controllers' => [
                        [
                            'name' => 'Index',
                            'comment' => '默认控制器',
                            'parent_controller' => '',
                            'actions' => [
                                ['name' => 'index', 'comment' => '默认方法']
                            ]
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
                            'name' => 'User',
                            'comment' => '用户',
                            'autoWriteTimeStamp' => true,
                            'fields' => [
                                ['name' => 'account', 'title' => '帐号', 'rule' => 'alpha', 'required' => true, 'is_unique' => true],
                                ['name' => 'password', 'title' => '密码', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'logo', 'title' => '头像地址', 'rule' => 'url', 'required' => false, 'is_unique' => false],
                                ['name' => 'ip', 'title' => 'IP地址', 'rule' => 'ip', 'required' => false, 'is_unique' => false],
                                ['name' => 'is_blocked', 'title' => '禁用', 'rule' => 'boolean', 'required' => true, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'UserGroup',
                                    'caption' => '所属分组',
                                    'type' => 'belongsTo',
                                    'this_key' => 'user_group_id',
                                    'that_key' => 'id',
                                    'model' => 'UserGroup'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Profile',
                            'comment' => '用户',
                            'autoWriteTimeStamp' => true,
                            'fields' => [
                                ['name' => 'name', 'title' => '姓名', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'gender', 'title' => '性别', 'rule' => 'number', 'required' => true, 'is_unique' => false],
                                ['name' => 'mobile', 'title' => '手机号码', 'rule' => 'number', 'required' => true, 'is_unique' => true]
                            ],
                            'relations' => [
                                [
                                    'name' => 'User',
                                    'caption' => '所属用户',
                                    'type' => 'belongsTo',
                                    'this_key' => 'user_id',
                                    'that_key' => 'id',
                                    'model' => 'User'
                                ]
                            ]
                        ],
                        [
                            'name' => 'UserGroup',
                            'comment' => '用户分组',
                            'autoWriteTimeStamp' => true,
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'Users',
                                    'caption' => '用户',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'user_group_id',
                                    'model' => 'User'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Role',
                            'comment' => '角色',
                            'autoWriteTimeStamp' => false,
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'Users',
                                    'caption' => '用户关系',
                                    'type' => 'belongsToMany',
                                    'this_key' => 'role_id',
                                    'that_key' => 'user_id',
                                    'model' => 'UserRole'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Node',
                            'comment' => '节点',
                            'autoWriteTimeStamp' => false,
                            'fields' => [
                                ['name' => 'caption', 'title' => '名称', 'rule' => 'chsAlpha', 'required' => false, 'is_unique' => false],
                                ['name' => 'type', 'title' => '类型', 'rule' => 'number', 'required' => false, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'Parent',
                                    'caption' => '父节点',
                                    'type' => 'belongsTo',
                                    'this_key' => 'parent_id',
                                    'that_key' => 'id',
                                    'model' => 'Node'
                                ],
                                [
                                    'name' => 'Children',
                                    'caption' => '子节点',
                                    'type' => 'hasMany',
                                    'this_key' => 'id',
                                    'that_key' => 'parent_id',
                                    'model' => 'Node'
                                ]
                            ]
                        ],
                        [
                            'name' => 'Access',
                            'comment' => '权限',
                            'autoWriteTimeStamp' => false,
                            'fields' => [
                                ['name' => 'is_allowed', 'title' => '权限', 'rule' => 'boolean', 'required' => true, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'Node',
                                    'caption' => '节点',
                                    'type' => 'hasOne',
                                    'this_key' => 'node_id',
                                    'that_key' => 'id',
                                    'model' => 'Node'
                                ],
                                [
                                    'name' => 'User',
                                    'caption' => '用户',
                                    'type' => 'hasOne',
                                    'this_key' => 'user_id',
                                    'that_key' => 'id',
                                    'model' => 'User'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
