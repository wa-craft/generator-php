<?php
return [
    //主题名
    'theme' => 'metronic_1',
    //默认值
    'defaults' => [
        'controller' => 'goldeagle\thinklib\controller\DefaultController',
        'default_theme' => 'metronic_default',
        //默认的控制器动作/视图模板
        'actions' => [
            ['name' => 'index', 'comment' => '列表'],
            ['name' => 'add', 'comment' => '添加'],
            ['name' => 'mod', 'comment' => '修改']
        ],
        //默认创建时间/更新时间的字段设置
        'autoTimeStampFields' => [
            ['name' => 'create_time', 'title' => '创建时间', 'rule' => 'datetime', 'required' => true, 'is_unique' => false],
            ['name' => 'update_time', 'title' => '修改时间', 'rule' => 'datetime', 'required' => true, 'is_unique' => false]
        ],
        'bower_deps' => [
            "bootstrap",
            "font-awesome",
            "ionicons",
            "simple-line-icons",
            "bootstrap-switch",
            "bootstrap-daterangepicker",
            "bootstrap-hover-dropdown",
            "jquery-slimscroll",
            "blockui"
        ]
    ]
];
