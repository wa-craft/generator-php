# think-builder
a cli tool to build applications for thinkphp v5，
通过定义数据对象的结构，自动生成相关的CRUD代码。

## 功能
* 从 github 获取并创建默认的目录结构
* 自动生成控制器程序以及CRUD方法
* 自动生成模型程序以及关联模型
* 自动生成后台校验器程序以及校验规则表
* 自动生成CRUD的视图界面（可以选择不同主题）
* 自动生成SQL数据库表结构
* 自动生成权限树
* 自动生成RBAC数据
* 自动生成功能菜单
* 自动生成 nginx vhost 配置文件
* 自动生成 .htaccess 配置文件

## 使用方法
1. 定义 project 目录下面的项目文件。
2. 获取 commando：`composer require nategood/commando`
3. linux 下面执行 `./builder` ；windows 下执行 `./build.bat`

>命令样例（linux）：

`#./builder -c config.php -p forge -t ./tmp -a all`

## builder 命令行参数
### `-c|--config`
指定配置文件的路径，默认为 './config.php'

### `-p|--project`
指定项目数据文件的路径，不需要包含 .php 后缀，文件必须位于 ./project 目录下，默认为 './project/project.php'

### `-a|--actions`
声明生成的动作，包括 'all|mvc|copy'，默认为'all'

### `-t|--target`
指定文件生成的目标路径，默认为 './deploy'