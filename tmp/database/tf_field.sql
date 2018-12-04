DROP TABLE IF EXISTS `tf_field`;
CREATE TABLE IF NOT EXISTS `tf_field` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`rule` varchar(100)  DEFAULT 'alpha'  COMMENT '取值约束，即校验规则，支持的校验规则请参考 thinkbuilder\node\Field::$rules',
	`is_required` tinyint(1) DEFAULT  DEFAULT '1'  COMMENT '是否为创建数据或更新数据时必须填充的内容',
	`is_unique` tinyint(1) DEFAULT  DEFAULT ''  COMMENT '表格中是否只允许唯一值',
	`is_auto` tinyint(1) DEFAULT  DEFAULT ''  COMMENT '是否为系统自动填充的字段，可以不进行定义，默认为 false',
	`default` varchar(100)  DEFAULT ''  COMMENT '默认值',
	`model_id` bigint(20)  DEFAULT '0'  COMMENT '模型',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 字段表';