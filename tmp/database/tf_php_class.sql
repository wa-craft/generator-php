DROP TABLE IF EXISTS `tf_php_class`;
CREATE TABLE IF NOT EXISTS `tf_php_class` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`type` bigint(20)  DEFAULT '0'  COMMENT '类的类型，包括：CRUD Controller|Plain Controller|Helper|Behavior|Trait',
	`module_id` bigint(20)  DEFAULT '0'  COMMENT '模块',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 类定义表';