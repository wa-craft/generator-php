DROP TABLE IF EXISTS `tf_trait`;
CREATE TABLE IF NOT EXISTS `tf_trait` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`module_id` bigint(20)  DEFAULT '0'  COMMENT '模块',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 泛型表';