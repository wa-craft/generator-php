<?php
return [
    //创建的动作
    'actions' => [
        //是否生成入口文件
        'portal' => true,
        //是否生成控制器程序
        'controller' => true,
        //是否生成特征程序
        'traits' => true,
        //是否生成模型程序
        'model' => true,
        //是否生成校验器程序
        'validate' => true,
        //是否生成视图模板文件
        'view' => true,
        //是否生成SQL数据库文件
        'sql' => true,
        //是否复制其他静态资源
        'copy' => true,
        //是否根据应用 portal 生成 nginx 虚拟主机配置文件，谨慎使用
        'nginx' => true,
        //是否根据应用 portal 生成 apache 虚拟主机配置文件，谨慎使用
        'apache' => true,
        //是否根据应用 portal 生成 apache .htaccess 配置文件，谨慎使用
        'apache_access' => true
    ],
    //默认值
    'defaults' => [
        'controller' => 'goldeagle\thinklib\controller\DefaultController',
        'theme' => 'metronic_1',
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
