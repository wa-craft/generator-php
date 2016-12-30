#TODO List

## v2.0.0+
* 命令：(*)增加 --create node:project|node:module|node:model|... 等创建节点的命令
* 命令：在创建节点命令中支持 namespace 写法
* 命令：增加 --set property:xxx 等设置属性的命令
* 命令：增加 --all | --only 命令，支持生成所有文件|只生成当前对象
* 命令：增加 --as-patch 命令，将生成的文件自动打包成补丁
* 命令：(*)增加 --reverse 命令，从数据库结构中逆向生成数据文件并生成代码
* 框架：重新规划目录结构，并增加对应的说明文档
* 框架：支持目录结构分层设置数据，并使用命令行方式在当前文件夹下面创建内容（提升易用性）
* 框架：支持编程方式设置数据，例如 new Project()->addModule() ...
* 框架：重构部分生成器代码
* 框架：增加全局数据对象缓存，全部节点设置完之后再生成代码
* 框架：(*)提供新旧版本数据对照功能，并自动删除无用的生成结果
* 框架：增加触发器 trigger，生成表结构之后触发生成数据表内容等
* 控制器：增加对 widget controller 的支持
* 控制器：(*)支持多级控制器，例如 index/user.view/index 为获取视图的控制器，
index/user.action/index 为相应请求的控制器，后者不需要继承自 think\controller
* 模型：支持单独生成 model 的相关代码
* 模型：(*)支持对新旧版本数据库结构对比后生成 alter.sql
* 模型：增加 autoTimeStamp 属性支持
* 视图：使用标准 bootstrap 作为 default 界面模板
* 视图：在模板节点类中增加 widget 属性
* 视图：在模板节点类中增加对 scriptlets 与 style/js 文件引入的管理
* 视图：支持用户创建自定义模板，并可在创建模板节点的时候进行引用
* 数据：提供完整的 rbac 数据定义，并可在用户数据中引用
* 数据：提供完整的 oauth 数据定义（符合 oauth2），并可在用户数据中引用
* 数据：提供完整的 forge 数据定义，可自动生成 b/s 界面版本的 think-builder，增强易用性
* 数据：提供完整的 cms 数据定义，可以自动生成基础的 CMS 前后台，需要 goldeagle\thinklib 支持
* 文档：基于 cms 数据定义，提供 Howto 文档，让用户可以自己定义并生成 CMS 系统
* 文档：完善每个代码文件中的相关说明帮助

## 其他
* 用 symfony console 代替 commando （探讨）
* 改用 api 模式开发，前后端分离，前端使用 vue.js
* 增强 tp5 框架，支持场景，场景包括对应的控制器->方法、模型->场景、校验器->场景、视图、Widget
* 增加 dist 目录，并将 php 代码打包成 phar
* 增强型数据表，提供搜索/排序等功能