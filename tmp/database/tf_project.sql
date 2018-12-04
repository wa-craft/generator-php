DROP TABLE IF EXISTS `tf_project`;
CREATE TABLE IF NOT EXISTS `tf_project` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`domain` varchar(100)  DEFAULT 'tf.vm'  COMMENT '说明',
	`domain_test` varchar(100)  DEFAULT 'tf.vm'  COMMENT '说明',
	`company` varchar(100)  DEFAULT ''  COMMENT '公司名称',
	`vendor` varchar(100)  DEFAULT '东莞培基'  COMMENT '供应商',
	`copyright` varchar(100)  DEFAULT '&copy; 2017'  COMMENT '版权信息',
	`is_external` tinyint(1) DEFAULT  DEFAULT ''  COMMENT '是否外部引入的与定义，不参与生成代码',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 项目表';