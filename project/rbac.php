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
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'author_id', 'title' => '用户编号', 'rule' => 'number', 'required' => false, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'UserGroup',
                                    'caption' => '所属分组',
                                    'type' => 'belongsTo',
                                    'this_key' => 'user_group_id',
                                    'that_key' => 'id',
                                    'model' => 'UserGroup'
                                ],
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
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'author_id', 'title' => '用户编号', 'rule' => 'number', 'required' => false, 'is_unique' => false]
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
                            'name' => 'UserGroup',
                            'comment' => '用户分组',
                            'autoWriteTimeStamp' => true,
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'author_id', 'title' => '用户编号', 'rule' => 'number', 'required' => false, 'is_unique' => false]
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
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'author_id', 'title' => '用户编号', 'rule' => 'number', 'required' => false, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'Users',
                                    'caption' => '用户',
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
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'author_id', 'title' => '用户编号', 'rule' => 'number', 'required' => false, 'is_unique' => false]
                            ],
                            'relations' => [
                                [
                                    'name' => 'Parent',
                                    'caption' => '父节点',
                                    'type' => 'belongsToMany',
                                    'this_key' => 'parent_id',
                                    'that_key' => 'id',
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
