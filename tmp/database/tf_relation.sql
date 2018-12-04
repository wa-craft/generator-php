DROP TABLE IF EXISTS `tf_relation`;
CREATE TABLE IF NOT EXISTS `tf_relation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`setting` varchar(100)  NOT NULL  COMMENT '设置',
	`value` varchar(100)  NOT NULL  COMMENT '取值',
	`author_id` bigint(20)  DEFAULT '0'  COMMENT '作者',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 设置表';