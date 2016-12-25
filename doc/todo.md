#TODO List

## 增强
### v1.3.4
* 提供 read 方法与视图
* 针对 datetime 提供 html 代码生成

### v2.0.0
* 增加 --create project|module|model|... 命令
* 在创建节点命令中支持 namespace 写法

## 修复
* 关联关系 this_key that_key 的问题
* 管理关系中 model 属性没有起到作用

## 思考
* 使用标准 bootstrap 提供界面
* 使用目录结构分层保存数据，并使用命令行方式在当前文件夹下面创建内容（提升易用性）
* 在模板节点类中增加 widget 属性
* 在木板节点类中增加对 scriptlets 与 style/js 文件引入的管理
* 用 symfony console 代替 commando
* 改用 api 模式开发，前端使用 vue.js