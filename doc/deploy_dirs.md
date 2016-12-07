## 生成代码后的目录结构

使用 think-builder 生成代码之后的目录结构与 ThinkPHP v5 的[标准目录结构](https://github.com/top-think/think) 是有一定区别的。
具体变化如下：

1. 若系统存在一个应用（application)，则在 tp5 标准目录结构中，存在 application 作为默认应用的目录结构，
而 tb 则把所有的应用放在 applications 目录下，则生成的应用目录在 /applications/application 下。

2. tp5 标准目录结构中，应用的命名空间与应用所在的目录结构可以不同，例如 application 的命名空间为 app，代码可以是在 /application 目录下；
而在 tb 中，生成的应用目录名称与应用的命名空间保持一致，则生成的应用目录应该是 /applications/app。

3. tp5 标准目录结构的 runtime 目录默认是在整个系统的根目录下；
而 tb 中根据应用生成的规则，自动生成在 /applications/runtime 目录下。

4. tb 生成的目录中，并未包含 think 命令。

5. tp5 标准目录结构，tp5 框架代码放在 /thinkphp 目录下；
而 tb 中把框架与其他 composer 包一视同仁，也就是框架代码在 /vendor/topthink/framework 目录下。

6. tb 相比 tp5 增加存放生成的 SQL 文件的 /database 目录。

7. tb 相比 tp5 增加了存放主机配置文件的 /profile 目录，现在默认会生成 nginx 的 vhost 配置文件。

8. tb 相比 tp5 增加了 bower 的配置文件，并且添加了 /.bowerrc 文件，bower 安装的文件会放置在 /public/vendor 目录下。

因此，一个标准 tb 生成的目录结构应该如下（以命名空间为 shop 的应用为例）：


~~~
deploy/ WEB部署目录（或者子目录）
├─applications/          应用的父目录
│  └─shop/               shop 应用的目录
│     ├─common/          公共模块目录（可以更改）
│     │
│     ├─extra/           扩展配置文件目录
│     │
│     ├─runtime/         应用的运行时目录（可写，可定制）    
│     │
│     ├─module_name/     模块目录
│     │  ├─config.php    模块配置文件
│     │  ├─common.php    模块函数文件
│     │  ├─controller/   控制器目录
│     │  ├─model/        模型目录
│     │  ├─view/         视图目录
│     │  ├─traits/       特性目录
│     │  └─ ...          更多类库目录
│     │ 
│     ├─command.php      命令行工具配置文件
│     ├─common.php       公共函数文件
│     ├─config.php       公共配置文件
│     ├─route.php        路由配置文件
│     ├─tags.php         应用行为扩展定义文件
│     └─database.php     数据库配置文件
│
├─database/              数据库SQL文件目录
│
├─profile/               虚拟主机配置文件目录
│
├─public/                WEB目录（对外访问目录）
│  ├─assets/             生成的资源文件目录
│  ├─vendor/             bower安装的资源文件目录
│  ├─index.php           入口文件
│  ├─router.php          快速测试文件
│  └─.htaccess           用于apache的重写
│
├─vendor/                第三方类库目录（Composer依赖库）
│  └─topthink/framework/ 框架系统目录
│     ├─lang/            语言文件目录
│     ├─library/         框架类库目录
│     │  ├─think/        Think类库包目录
│     │  └─traits/       系统Trait目录
│     │
│     ├─tpl/             系统模板目录
│     ├─base.php         基础定义文件
│     ├─console.php      控制台入口文件
│     ├─convention.php   框架惯例配置文件
│     ├─helper.php       助手函数文件
│     ├─phpunit.xml      phpunit配置文件
│     └─start.php        框架入口文件
├─.bowerrc               bower环境配置文件
├─bower.json             bower定义文件
├─composer.json          composer 定义文件
~~~