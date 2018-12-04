DROP TABLE IF EXISTS `tf_action`;
CREATE TABLE IF NOT EXISTS `tf_action` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` varchar(100)  DEFAULT ''  COMMENT '名称',
	`caption` varchar(100)  DEFAULT ''  COMMENT '说明',
	`is_abstract` tinyint(1) DEFAULT  DEFAULT ''  COMMENT '是否是抽象的',
	`is_static` tinyint(1) DEFAULT  DEFAULT ''  COMMENT '是否是静态的',
	`scope_id` bigint(20)  DEFAULT '0'  COMMENT '作用域，包括 public|protected|private',
	`class_id` bigint(20)  DEFAULT '0'  COMMENT '所属对象',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tf|index 方法表';