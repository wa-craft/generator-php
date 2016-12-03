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
            'caption' => '测试应用',
            'modules' => [
                [
                    'name' => 'index',
                    'caption' => '默认模块',
                    'default_controller' => '',
                    'controllers' => [
                        [
                            'name' => 'Index',
                            'caption' => '默认控制器',
                            'parent_controller' => '',
                            'actions' => [
                                ['name' => 'index', 'caption' => '默认方法']
                            ]
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
                            'name' => 'Article',
                            'caption' => '文章',
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
