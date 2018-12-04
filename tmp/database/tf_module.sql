DROP TABLE IF EXISTS `tf_module`;
CREATE TABLE IF NOT EXISTS `tf_module` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`theme_id` bigint(20)  DEFAULT '0'  COMMENT '主题',
	`application_id` bigint(20)  DEFAULT '0'  COMMENT '应用',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 模块表';