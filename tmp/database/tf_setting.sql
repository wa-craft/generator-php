DROP TABLE IF EXISTS `tf_setting`;
CREATE TABLE IF NOT EXISTS `tf_setting` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`keyword` varchar(100)  DEFAULT ''  COMMENT '键',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`value` varchar(100)  DEFAULT ''  COMMENT '值',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 设置表';