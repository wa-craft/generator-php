# think-builder
[![Latest Stable Version](https://poser.pugx.org/goldeagle/think-builder/version)](https://packagist.org/packages/goldeagle/think-builder)
[![Latest Unstable Version](https://poser.pugx.org/goldeagle/think-builder/v/unstable)](//packagist.org/packages/goldeagle/think-builder)
[![License](https://poser.pugx.org/goldeagle/think-builder/license)](https://packagist.org/packages/goldeagle/think-builder)
[![composer.lock available](https://poser.pugx.org/goldeagle/think-builder/composerlock)](https://packagist.org/packages/goldeagle/think-builder)

a cli tool to build applications for thinkphp v5，
通过定义数据对象的结构，自动生成相关的CRUD代码。

## 1. 功能
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

## 2. 环境需求
### 2.1. Linux （以 Debian 为例）
#### 2.1.1. 安装 git
`#apt install git`
#### 2.1.2. 安装 composer
* 去 getcomposer.org 下载 composer.phar 文件
* 拷贝到 /usr/local/bin 目录下，
* 并改名为：/usr/local/bin/composer
* 记得赋予可执行权限：
`#chmod a+x /usr/local/bin/composer`
#### 2.1.3. 安装 PHP
`#apt instal php7.0-mbstring php7.0-ssl php7.0-fpm php7.0-curl`

### 2.2. Windows
#### 2.2.1. 安装 git
* 去 [https://git-for-windows.github.io/](https://git-for-windows.github.io/) 下载
* 安装 git for windows，例如安装到 d:\env\git 目录下
* 记得把路径加入到 path 环境变量，例如 d:\env\git\bin
#### 2.2.2. 安装 composer
* 去 getcomposer.org 下载 composer.phar 文件
* 下载到指定目录，例如 d:\env\composer
* 创建一个批处理文件，例如 d:\env\composer\composer.bat
写入：`@php "%~dp0composer.phar" %*` 到这个批处理文件
* 把路径加入到 path 环境变量，例如 d:\env\composer
#### 2.2.3. 安装 PHP
* 去 [http://windows.php.net/download#php-7.0](http://windows.php.net/download#php-7.0) 下载最新版本的 PHP
* 安装到本地，例如 d:\env\php
* 创建 php.ini 文件，比如把 php.ini-development 拷贝成 php.ini 文件
* 修改 php.ini 文件，去掉 php_mbstring.dll 与 php_openssl.dll 之前的`;`，启用扩展

### 2.3. macOS
#### 2.3.1 安装 homebrew
`/#sudo usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`
#### 2.3.2 安装 git
macOS 一般默认都安装了 git，不过也可以 `#sudo brew install git`
#### 2.3.3 安装 composer
`#sudo brew install josegonzalez/php/composer`
#### 2.3.4 安装 PHP
`#sudo brew install php70
 --with-fpm
 --with-mbstring
 --with-homebrew-openssl
 --with-mysql
 --with-libmysql`

## 3. 使用方法
1. 定义 project 目录下面的项目文件。
2. 获取 commando：`composer update`，在 v1.2.0 以后的版本，这一步自动完成
3. linux 下面执行 `./build` ；windows 下执行 `./build.bat`

>命令样例（linux）：

`#./build -c config.php -d ./sample/forge.php -t ./tmp -a all`

## 4. builder 命令行参数
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