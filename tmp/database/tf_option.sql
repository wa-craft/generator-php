DROP TABLE IF EXISTS `tf_option`;
CREATE TABLE IF NOT EXISTS `tf_option` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`keyword` varchar(100)  DEFAULT ''  COMMENT '键',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`value` varchar(100)  DEFAULT ''  COMMENT '值',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 设置表';