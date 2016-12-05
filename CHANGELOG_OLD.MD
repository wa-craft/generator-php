## v1.2.0
* 增加：可以预定义模块数据以及 pkg 目录，并可在项目数据中引用
* 增加：对主题的支持
* 增加：命令行 -d|--data 参数
* 修改：重新规划代码目录，去除 src/lib 目录，增加 src/thinkbuilder 目录
* 修改：采用面向对象的方式进行重构
* 修改：自动装载器使用 spl_autoload 的相关方式进行注册与装载
* 修改：独立资源目录 assets，去除 share 目录
* 修改：去除 projects 目录，增加 sample 目录
* 修改：部分代码采用 PHP 7.0 标准重写
* 修改：去除命令行 -p|--project 参数

## v1.1.0
* 增加控制器 extends 指定
* 增加 traits 生成
* 增加模型外键类指定
* 增加模型 hasOne hasMany belongsTo belongsToMany 方法生成
* 增加默认主题的资源文件
* 增加 layout 文件的写入
* 增加驼峰与下划线互相转换的方法
* 在生成代码过后，自动在目标目录中执行 composer update 命令
* 在生成代码过后，自动在目标目录中执行 bower install --save 命令
* 性能优化

## v1.0.0
* 基于 nategood/commando 加入命令行支持
* 使用 oo 的架构重写代码

## v0.6.5
* 去除 write_template_file 方法

## v0.6.4
* 修正 view 没有正确生成 form action 路径的错误
* 修正控制器保存方法跳转的错误
* 增加 model 的 autoWriteTimeStamp
* 增加对配置文件的写入

## v0.6.3
* 增加生成 boolean 类型时的默认值
* 增加 ckeditor 与绑定
* 增加 text 规则的 html/sql 自动生成
* 增加菜单定义 icon
* 修改 view 模板的相关 icon

## v0.6.2
* 增加 login/register/logout 的模板
* 修复生成模型类namespace的错误

## v0.6.1
* 增加一些新的默认校验规则
* 增加对日期时间的规则的生成处理
* 增加对邮箱规则的生成处理

## v0.6.0
* 数据结构增加 project 的设置
* 增加 nginx 配置文件的默认生成
* 自动生成 .htaccess 文件

## v0.5.1
* 增加 portal 的 app_path 设置
* 增加 git clone 创建默认的目录
* 修改创建目录的默认权限为 0744

## v0.5.0
* 与 think-forge 分离，成为独立的命令行工具

## v0.4.0
* 生成SQL文件
* 移动 build_actions
* 移除 autoload 以及相关的 php 文件


## v0.3.0
* 生成界面模板
* 拷贝基本文件
* 生成入口文件
* 方法分离

## v0.2.0
* 生成控制器
* 生成模型
* 生成基本校验器
* 增加运行脚本

## v0.1.0
* 基本的程序框架
* 生成基本目录结构