<?php
/**
 * 样例
 */
return [
    //项目名称，全部小写即可
    'name' => 'sample',
    //项目的正式域名
    'domain' => 'sample.com',
    //项目的测试域名
    'domain_test' => 'sample.com',
    //公司名称
    'company' => '东莞培基',
    //供应商信息
    'vendor' => '东莞培基',
    //版权信息
    'copyright' => '&copy;2016-2017',
    //生成代码时执行的命令
    'commands' => [
        [
            //执行的命令
            'command' => 'ls',
            //说明
            'comment' => '列表',
            //是否是生成代码之前运行，false 的话就是在生成代码之后运行
            'is_before' => true
        ]
    ],
    //应用列表
    'applications' => [
        [
            //应用名称，全部小写即可
            'name' => 'sample',
            //应用的说明
            'caption' => '测试应用',
            //应用的命名空间，小写
            'namespace' => 'app',
            //应用的入口文件，小写
            'portal' => 'index',
            //是否自动生成 menu 配置文件，可以不进行定义，默认为 true
            'autoMenu' => true,
            //模块列表
            'modules' => [
                //引用式定义，冒号左边的是引用的预定义模块名称，冒号右边的是在此项目中的模块名称，若没有冒号则视为原名引入
                'rbac:auth',
                [
                    //模块名称，小写
                    'name' => 'index',
                    //模块说明
                    'caption' => '默认模块',
                    //所有CRUD控制器共用的默认父类，注意定义时最好为双斜线，例如：'\\think\\Controller'
                    'default_controller' => '',
                    //视图模板使用的主题名称，在 template/html 与 assets/themes 中应有对应的目录
                    'theme' => 'metronic_1',
                    //CRUD模式列表，CRUD模式会自动创建对应的控制器、模型、校验器、视图、SQL数据表代码。
                    'schemas' => [
                        [
                            //模式名称，使用驼峰式定义
                            'name' => 'Article',
                            //模式说明
                            'caption' => '文章',
                            //是否自动创建 create_time、update_time 模型属性
                            'autoWriteTimeStamp' => true,
                            //字段列表
                            'fields' => [
                                [
                                    //字段名称，使用驼峰式定义
                                    'name' => 'Title',
                                    //说明
                                    'caption' => '标题',
                                    //取值约束，即校验规则，支持的校验规则请参考 thinkbuilder\node\Field::$rules
                                    'rule' => 'alpha',
                                    //是否为创建数据或更新数据时必须填充的内容
                                    'required' => true,
                                    //表格中是否只允许唯一值
                                    'is_unique' => false,
                                    //是否为系统自动填充的字段，可以不进行定义，默认为 false
                                    'is_auto' => false,
                                    //默认值
                                    'default' => ''
                                ],
                                ['name' => 'content', 'caption' => '正文', 'rule' => 'text', 'required' => false, 'is_unique' => false]
                            ],
                            //模式关联列表，用于生成数据库中的外键、数据模型中的关联引用
                            'relations' => [
                                [
                                    //关联名称，使用驼峰式定义，首字母小写
                                    'name' => 'author',
                                    //关联说明
                                    'caption' => '作者',
                                    //关联类型，支持 hasOne|hasMany|belongsTo|belongsToMany|ManyToMany
                                    'type' => 'hasOne',
                                    //在当前模式或模型中的字段名称
                                    'this_key' => 'author_id',
                                    //在引用的模式或模型中的字段名称
                                    'that_key' => 'id',
                                    //引用的模式或模型
                                    'model' => 'User'
                                ]
                            ]
                        ]
                    ],
                    //独立控制器列表，可以不进行定义
                    'controllers' => [
                        [
                            //控制器名称
                            'name' => 'Index',
                            //控制器说明
                            'caption' => '默认控制器',
                            //控制器的父类，若不指定，则生成的代码自动继承 \think\Controller
                            'parent_controller' => '',
                            //动作类表，会根据动作列表自动生成对应的控制器方法与视图界面
                            'actions' => [
                                [
                                    //动作名称
                                    'name' => 'index',
                                    //动作说明
                                    'caption' => '列表',
                                    //动作参数，会在生成方法代码的时候自动添加在方法的 () 中
                                    'params' => '',
                                    //是否是静态方法，可以不进行定义，默认为 false
                                    'static' => false
                                ]
                            ]
                        ]
                    ],
                    //助手列表，可以不进行定义
                    'helpers' => [
                        [
                            //控制器名称
                            'name' => 'FileHelper',
                            //控制器说明
                            'caption' => '文件助手',
                            //控制器的父类，可以为空
                            'parent_controller' => '',
                            //动作类表，会根据动作列表自动生成对应的控制器方法与视图界面，助手程序的 action 全部为静态方法
                            'actions' => [
                                [
                                    //动作名称
                                    'name' => 'index',
                                    //动作说明
                                    'caption' => '列表',
                                    //动作参数，会在生成方法代码的时候自动添加在方法的 () 中
                                    'params' => '',
                                    //是否是静态方法，可以不进行定义，默认为 false
                                    'static' => false
                                ]
                            ]
                        ]
                    ],
                    //行为列表，可以不进行定义
                    'behaviors' => [
                        [
                            //行为名称
                            'name' => 'CheckAuth',
                            //行为说明
                            'caption' => '认证行为',
                            //行为的父类，可以为空
                            'parent_controller' => '',
                            //动作类表，会根据动作列表自动生成对应的行为方法
                            'actions' => [
                                [
                                    //动作名称
                                    'name' => 'app_init',
                                    //动作说明
                                    'caption' => '列表',
                                    //动作参数，会在生成方法代码的时候自动添加在方法的 () 中
                                    'params' => '&$params'
                                ]
                            ]
                        ]
                    ],
                    //特性列表，可以不进行定义
                    'traitss' => [
                        [
                            //特性名称
                            'name' => 'Auth',
                            //特性说明
                            'caption' => '认证方法',
                            //特性动作列表
                            'actions' => [
                                [
                                    'name' => 'check',
                                    'caption' => '校验',
                                    'params' => '$username, $password'
                                ]
                            ]
                        ]
                    ],
                    //独立模型列表，独立模型只会创建模型、校验器、SQL代码，不会创建CRUD控制器与视图，可以不进行定义
                    'models' => [
                        [
                            //模型定义的说明参见模式说明
                            'name' => 'User',
                            'caption' => '用户',
                            'autoWriteTimeStamp' => true,
                            'fields' => [
                                [
                                    'name' => 'name',
                                    'caption' => '名称',
                                    'rule' => 'alpha',
                                    'required' => true,
                                    'is_unique' => false
                                ],
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
