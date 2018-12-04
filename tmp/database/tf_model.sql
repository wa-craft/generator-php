DROP TABLE IF EXISTS `tf_model`;
CREATE TABLE IF NOT EXISTS `tf_model` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`autoWriteTimeStamp` varchar(100)  DEFAULT 'datetime'  COMMENT '是否自动创建 create_time、update_time 模型属性',
	`module_id` bigint(20)  DEFAULT '0'  COMMENT '模块',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 模型表';