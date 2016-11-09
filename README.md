# think-builder
a cli tool to build applications for thinkphp v5，
通过定义数据对象的结构，自动生成相关的CRUD代码。

## 功能
* 从 github 获取并创建默认的目录结构
* 自动生成控制器程序以及CRUD方法
* 自动生成模型程序以及关联模型
* 自动生成后台校验器程序以及校验规则表
* 自动生成CRUD的视图界面（基于 metronic）
* 自动生成SQL数据库表结构
* 自动生成权限树
* 自动生成RBAC数据
* 自动生成功能菜单

## 使用方法
1. 定义 project 目录下面的项目文件。
2. linux 下面执行 ./builder 命令；windows 下执行 ./build.bat
4. 将 deploy 目录下生成的文件夹拷贝到项目根目录即可。