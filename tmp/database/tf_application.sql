DROP TABLE IF EXISTS `tf_application`;
CREATE TABLE IF NOT EXISTS `tf_application` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`namespace` varchar(100)  DEFAULT 'app'  COMMENT '应用的命名空间，小写',
	`portal` varchar(100)  DEFAULT 'index'  COMMENT '应用的入口文件，小写',
	`auto_menu` tinyint(1) DEFAULT  DEFAULT '1'  COMMENT '是否自动生成 menu 配置文件，可以不进行定义，默认为 true',
	`project_id` bigint(20)  DEFAULT '0'  COMMENT '项目',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 应用表';