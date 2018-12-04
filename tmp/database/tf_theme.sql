DROP TABLE IF EXISTS `tf_theme`;
CREATE TABLE IF NOT EXISTS `tf_theme` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`module_id` bigint(20)  NOT NULL  COMMENT '模块编号',
	`name` varchar(100)  NOT NULL  COMMENT '名称',
	`annotation` varchar(100)  NOT NULL  COMMENT '说明',
	`module_id` bigint(20)  DEFAULT '0'  COMMENT '模块',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 主题表';