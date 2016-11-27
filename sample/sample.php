<?php
/**
 * 样例
 */
return [
    'name' => 'forge',
    'domain' => 'tf.vm',
    'applications' => [
        [
            'name' => 'application',
            'namespace' => 'app',
            'portal' => 'index',
            'comment' => '',
            'modules' => [
                [
                    'name' => 'index',
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
                            'name' => 'Article',
                            'comment' => '文章',
                            'autoWriteTimeStamp' => true,
                            'fields' => [
                                ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false],
                                ['name' => 'author_id', 'title' => '用户编号', 'rule' => 'number', 'required' => false, 'is_unique' => false]
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
