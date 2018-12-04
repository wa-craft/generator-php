DROP TABLE IF EXISTS `tf_command`;
CREATE TABLE IF NOT EXISTS `tf_command` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`command` varchar(100)  DEFAULT 'ls'  COMMENT '命令',
	`comment` varchar(100)  DEFAULT ''  COMMENT '说明',
	`is_before` tinyint(1) DEFAULT  DEFAULT '1'  COMMENT '是否是生成代码之前运行，false 的话就是在生成代码之后运行',
	`project_id` bigint(20)  DEFAULT '0'  COMMENT '项目',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 命令表';