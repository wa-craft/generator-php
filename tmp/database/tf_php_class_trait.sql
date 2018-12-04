DROP TABLE IF EXISTS `tf_php_class_trait`;
CREATE TABLE IF NOT EXISTS `tf_php_class_trait` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`class_id` bigint(20)  DEFAULT '0'  COMMENT '类',
	`trait_id` bigint(20)  DEFAULT '0'  COMMENT '泛型',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 类与泛型映射关系表';