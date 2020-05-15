CREATE TABLE `auth` (
  `auth_id` int(10) unsigned NOT NULL AUTO_INCREMENT comment '权限id',
  `name` varchar(50) NOT NULL default '' comment '权限名称',
  `url` varchar(255) NOT NULL default '' comment '权限url',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '权限类型：0-页面权限，1-接口权限',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '上级权限id',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '权限表';

CREATE TABLE `auth_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT comment '角色id',
  `name` varchar(50) NOT NULL default '' comment '角色名称',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '权限角色表';

CREATE TABLE `role_auth_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '角色id',
  `auth_id` int(10) NOT NULL DEFAULT '0' COMMENT '权限id',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '角色权限关联表';

CREATE TABLE `user_auth_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '权限角色id',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '用户权限角色关联表';