# think-builder
[![Latest Stable Version](https://poser.pugx.org/goldeagle/think-builder/version)](https://packagist.org/packages/goldeagle/think-builder)
[![Latest Unstable Version](https://poser.pugx.org/goldeagle/think-builder/v/unstable)](//packagist.org/packages/goldeagle/think-builder)
[![License](https://poser.pugx.org/goldeagle/think-builder/license)](https://packagist.org/packages/goldeagle/think-builder)
[![composer.lock available](https://poser.pugx.org/goldeagle/think-builder/composerlock)](https://packagist.org/packages/goldeagle/think-builder)

a cli tool to build applications for thinkphp v5，
通过定义数据对象的结构，自动生成相关的CRUD代码。

## 功能
* 从 github 获取并创建默认的目录结构
* 自动生成控制器程序以及CRUD方法
* 自动生成模型程序以及关联模型
* 自动生成后台校验器程序以及校验规则表
* 自动生成CRUD的视图界面
* 自动生成SQL数据库表结构
* 自动生成权限树 (TODO)
* 自动生成 RBAC 数据（TODO）
* 自动生成 nginx vhost 配置文件
* 自动生成 .htaccess 配置文件
* 完备的文档（TODO）

## 环境需求
* 操作系统为 Linux | Windows | macOS
* Git 命令
* PHP 7.0 以上版本
* PHP 需要加入 mb_string 支持
* PHP 需要加入 openssl 支持

## 使用方法
1. 定义 project 目录下面的项目文件。
2. 获取 commando：`composer update`
3. linux 下面执行 `./build` ；windows 下执行 `./build.bat`

>命令样例（linux）：

`#./build -c config.php -d ./sample/forge.php -t ./tmp -a all`

## builder 命令行参数
### `-c|--config`
指定配置文件的路径，默认为 './src/config.php'

### `-d|--data`
指定项目数据文件的路径

### `-a|--actions`
声明生成的动作，包括 'all|mvc|copy'，默认为'all'

### `-t|--target`
指定文件生成的目标路径，默认为 './deploy'

## 交流方式
QQ 群：348077414