## 环境需求

### Linux （以 Debian 为例）

#### 安装 git
`#apt install git`

#### 安装 composer
* 去 getcomposer.org 下载 composer.phar 文件
* 拷贝到 /usr/local/bin 目录下，
* 并改名为：/usr/local/bin/composer
* 记得赋予可执行权限：
`#chmod a+x /usr/local/bin/composer` 

#### 安装 PHP
`#apt instal php7.0-mbstring php7.0-ssl php7.0-fpm php7.0-curl`

### Windows

#### 安装 git
* 去 [https://git-for-windows.github.io/](https://git-for-windows.github.io/) 下载
* 安装 git for windows，例如安装到 d:\env\git 目录下
* 记得把路径加入到 path 环境变量，例如 d:\env\git\bin

#### 安装 composer
* 去 getcomposer.org 下载 composer.phar 文件
* 下载到指定目录，例如 d:\env\composer
* 创建一个批处理文件，例如 d:\env\composer\composer.bat
写入：`@php "%~dp0composer.phar" %*` 到这个批处理文件
* 把路径加入到 path 环境变量，例如 d:\env\composer

#### 安装 PHP
* 去 [http://windows.php.net/download#php-7.0](http://windows.php.net/download#php-7.0) 下载最新版本的 PHP
* 安装到本地，例如 d:\env\php
* 创建 php.ini 文件，比如把 php.ini-development 拷贝成 php.ini 文件
* 修改 php.ini 文件，去掉 php_mbstring.dll 与 php_openssl.dll 之前的`;`，启用扩展

### macOS
#### 安装 homebrew
`/#sudo usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`

#### 安装 git
macOS 一般默认都安装了 git，不过也可以 `#sudo brew install git` 

#### 安装 composer
`#sudo brew install josegonzalez/php/composer`

#### 安装 PHP
`#sudo brew install php70
 --with-fpm
 --with-mbstring
 --with-homebrew-openssl
 --with-mysql
 --with-libmysql`
 
### 其他
* 需要 tar 命令

[返回readme](../README.md)