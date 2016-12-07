#CHANGELOG

## v1.3.1
* 增加：对 float 字段类型的支持
* 增加：生成代码的目录结构说明文档
* 增加：标准类模板，并与 CRUD 控制器模板进行区分
* 修改：去除对 bforge-think 的依赖，系统自动创建需要的目录
* 修改：去除 -r | --repos 命令
* 修改：生成代码后，不再自动执行 composer/bower 命令
* 修正：生成 SQL 时读取 default 键值错误

## v1.3.0
* 代码重构，使用 oo 的方式进行重写
* 增加：命令行 -r|--repository 参数，指定应用基本目录结构的 git 仓库
* 增加：生成器相关代码
* 增加：image 类型 field 字段的处理方式
* 增加：模块 extend/menu 的生成
* 增加：field 字段数据现在可以设置 default 值，并在生成 sql/html 的时候体现
* 修改：完善 RBAC 预定义数据
* 修改：模板/数据定义中原有 comment 部分由 caption 代替，避免与 comment 字段定义混淆
* 修改：修正了模板中的一些错误定义
* 修改：更新日志仅保留次版本号与最新版一致的，其他的移到 CHANGELOG_OLD.MD

[旧的更新日志](./CHANGELOG_OLD.MD "旧的更新日志")