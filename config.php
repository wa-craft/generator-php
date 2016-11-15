<?php
return [
    //默认值
    'defaults' => [
        'controller' => 'goldeagle\thinklib\controller\DefaultController',
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
            'url' => '合法的 uri 网址',
            'ip' => '合法的 ip 地址',
            'money' => '金额'
        ]
    ],
    //模板路径
    'templates' => [
        'portal' => file_get_contents(TMPL_PATH . '/php/index.tmpl'),
        'controller' => file_get_contents(TMPL_PATH . '/php/controller.tmpl'),
        'controller_action' => file_get_contents(TMPL_PATH . '/php/controller_action.tmpl'),
        'traits' => file_get_contents(TMPL_PATH . '/php/traits.tmpl'),
        'traits_action' => file_get_contents(TMPL_PATH . '/php/traits_action.tmpl'),
        'model' => file_get_contents(TMPL_PATH . '/php/model.tmpl'),
        'model_relation' => file_get_contents(TMPL_PATH . '/php/model_relation.tmpl'),
        'validate' => file_get_contents(TMPL_PATH . '/php/validate.tmpl'),
        'view_add' => file_get_contents(TMPL_PATH . '/html/add.html'),
        'view_index' => file_get_contents(TMPL_PATH . '/html/index.html'),
        'view_controller_index' => file_get_contents(TMPL_PATH . '/html/controller_index.html'),
        'view_mod' => file_get_contents(TMPL_PATH . '/html/mod.html'),
        'view_add_field' => file_get_contents(TMPL_PATH . '/html/add_field.html'),
        'view_index_field' => file_get_contents(TMPL_PATH . '/html/index_field.html'),
        'view_mod_field' => file_get_contents(TMPL_PATH . '/html/mod_field.html'),
        'view_login' => file_get_contents(TMPL_PATH . '/html/login.html'),
        'view_register' => file_get_contents(TMPL_PATH . '/html/register.html'),
        'view_logout' => file_get_contents(TMPL_PATH . '/html/logout.html'),
        'sql_table' => file_get_contents(TMPL_PATH . '/sql/table.sql'),
        'nginx' => file_get_contents(TMPL_PATH . '/misc/nginx_vhost'),
        'apache' => file_get_contents(TMPL_PATH . '/misc/apache_access'),
        'config' => file_get_contents(TMPL_PATH . '/php/config.tmpl'),
        'database' => file_get_contents(TMPL_PATH . '/php/database.tmpl')
    ]

];
