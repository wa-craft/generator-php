<?php
/**
 * 样例
 */
return [
    [
        'name' => 'application',
        'namespace' => 'app',
        'portal' => 'index',
        'comment' => '',
        'modules' => [
            [
                'name' => 'index',
                'comment' => '默认模块',
                'controllers' => [
                    [
                        'name' => 'Index',
                        'comment' => '默认控制器',
                        'actions' => [
                            ['name' =>'index', 'comment' => '默认方法']
                        ]
                    ]
                ],
                'models' => [
                    [
                        'name' => 'Article',
                        'comment' => '文章',
                        'fields' => [
                            ['name' => 'name', 'title' => '名称', 'rule' => 'alpha', 'required' => true, 'is_unique' => false]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
