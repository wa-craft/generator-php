<?php
return [
    //生成的动作配置
    'build_actions' => [
        //是否生成入口文件
        'portal' => true,
        //是否生成控制器程序
        'controller' => true,
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
        //是否根据应用 portal 生成 nginx 配置文件，谨慎使用
        'nginx' => true,
        //是否根据应用 portal 生成 .htaccess，谨慎使用
        'apache' => true
    ],
    //默认值
    'defaults' => [
        //默认的控制器动作/视图模板
        'actions' => [
            ['name' => 'index', 'comment' => '列表'],
            ['name' => 'add', 'comment' => '添加'],
            ['name' => 'mod', 'comment' => '修改']
        ],
        //默认的校验规则列表
        'rules' => [
            'alpha' => '英文字符',
            'number' => '数字',
            'chsAlpha' => '中文或英文字符',
            'text' => '任何文字',
            'datetime' => '日期时间',
            'alphaDash' => '英文字符与下划线',
            'email' => '电子邮箱',
            'boolean' => '是/否',
            'url' => '合法的 uri 网址'
        ]
    ],
    //模板路径
    'templates' => [
        'portal' => file_get_contents(TMPL_PATH . '/php/index.tmpl'),
        'controller' => file_get_contents(TMPL_PATH . '/php/controller.tmpl'),
        'controller_action' => file_get_contents(TMPL_PATH . '/php/controller_action.tmpl'),
        'model' => file_get_contents(TMPL_PATH . '/php/model.tmpl'),
        'validate' => file_get_contents(TMPL_PATH . '/php/validate.tmpl'),
        'view_add' => file_get_contents(TMPL_PATH . '/html/add.html'),
        'view_index' => file_get_contents(TMPL_PATH . '/html/index.html'),
        'view_mod' => file_get_contents(TMPL_PATH . '/html/mod.html'),
        'view_add_field' => file_get_contents(TMPL_PATH . '/html/add_field.html'),
        'view_index_field' => file_get_contents(TMPL_PATH . '/html/index_field.html'),
        'view_mod_field' => file_get_contents(TMPL_PATH . '/html/mod_field.html'),
        'view_login' => file_get_contents(TMPL_PATH . '/html/login.html'),
        'view_register' => file_get_contents(TMPL_PATH . '/html/register.html'),
        'view_logout' => file_get_contents(TMPL_PATH . '/html/logout.html'),
        'sql_table' => file_get_contents(TMPL_PATH . '/sql/table.sql'),
        'nginx' => file_get_contents(TMPL_PATH . '/misc/nginx_vhost'),
        'apache' => file_get_contents(TMPL_PATH . '/misc/apache_access')
    ]

];
