<?php
return [
    'build_actions' => [
        'portal' => true,
        'controller' => true,
        'model' => true,
        'validate' => true,
        'view' => true,
        'sql' => true,
        'copy' => true
    ],
    'defaults' => [
        'actions' => [
            ['name' => 'index', 'comment' => '列表'],
            ['name' => 'add', 'comment' => '添加'],
            ['name' => 'mod', 'comment' => '修改']
        ],
        'rules' => [
            'alpha' => '英文字符',
            'number' => '数字',
            'chsAlpha' => '中文或英文字符',
            'alphaDash' => '英文字符与下划线',
            'email' => '电子邮箱',
            'boolean' => '是/否'
        ]
    ],
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
        'sql_table' => file_get_contents(TMPL_PATH . '/sql/table.sql')
    ]

];
