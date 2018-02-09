/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : tp5

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-02-09 16:26:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_access
-- ----------------------------
DROP TABLE IF EXISTS `tp_access`;
CREATE TABLE `tp_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `rule_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'url节点',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_access
-- ----------------------------
INSERT INTO `tp_access` VALUES ('35', '2', 'xtgl');
INSERT INTO `tp_access` VALUES ('36', '2', '/admin/admins/index');
INSERT INTO `tp_access` VALUES ('37', '2', '/admin/admins/add');
INSERT INTO `tp_access` VALUES ('38', '2', '/admin/admins/edit');
INSERT INTO `tp_access` VALUES ('39', '2', '/admin/admins/delete');
INSERT INTO `tp_access` VALUES ('40', '2', '/admin/role/index');
INSERT INTO `tp_access` VALUES ('41', '2', '/admin/access/authorize');
INSERT INTO `tp_access` VALUES ('42', '2', '/admin/role/add');
INSERT INTO `tp_access` VALUES ('43', '2', '/admin/role/edit');
INSERT INTO `tp_access` VALUES ('44', '2', '/admin/role/delete');
INSERT INTO `tp_access` VALUES ('45', '2', '/admin/menu/index');
INSERT INTO `tp_access` VALUES ('46', '2', '/admin/menu/add');
INSERT INTO `tp_access` VALUES ('47', '2', '/admin/menu/edit');
INSERT INTO `tp_access` VALUES ('48', '2', '/admin/menu/delete');
INSERT INTO `tp_access` VALUES ('49', '2', 'dygl');
INSERT INTO `tp_access` VALUES ('50', '2', '/admin/guide/index');
INSERT INTO `tp_access` VALUES ('51', '2', '/admin/guide/add');
INSERT INTO `tp_access` VALUES ('52', '2', '/admin/guide/edit');
INSERT INTO `tp_access` VALUES ('53', '2', '/admin/guide/del');
INSERT INTO `tp_access` VALUES ('54', '2', '/admin/user/index');
INSERT INTO `tp_access` VALUES ('55', '2', 'wzgl');
INSERT INTO `tp_access` VALUES ('56', '2', '/admin/article/index');
INSERT INTO `tp_access` VALUES ('57', '2', '/admin/article/add');
INSERT INTO `tp_access` VALUES ('58', '2', '/admin/article/edit');
INSERT INTO `tp_access` VALUES ('59', '2', '/admin/article/delete');

-- ----------------------------
-- Table structure for tp_admins
-- ----------------------------
DROP TABLE IF EXISTS `tp_admins`;
CREATE TABLE `tp_admins` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `realname` varchar(255) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(6) NOT NULL DEFAULT '' COMMENT '密码盐',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` char(15) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `accountpassword` (`account`,`password`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admins
-- ----------------------------
INSERT INTO `tp_admins` VALUES ('1', 'admin', '超级管理员', 'b49e788d80329bbf77386046f4763cc9', 'Ua3mU7', '1518083103', '127.0.0.1', 'tianpian0805@gmail.com', '13121126162', '1', '1', '0', '1222907803', '1517993669');
INSERT INTO `tp_admins` VALUES ('3', 'test333', '测试', 'b49e788d80329bbf77386046f4763cc9', 'Ua3mU7', '0', '', '45454@qq.com', '13225962992', '2', '1', '0', '1517992349', '1517993703');

-- ----------------------------
-- Table structure for tp_menu
-- ----------------------------
DROP TABLE IF EXISTS `tp_menu`;
CREATE TABLE `tp_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(50) NOT NULL DEFAULT '' COMMENT '链接',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1显示 0隐藏',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '菜单等级',
  `ismenu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是后台菜单 1是 0不是',
  `islog` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否需要记录操作日志  1 是 0 否',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_menu
-- ----------------------------
INSERT INTO `tp_menu` VALUES ('1', 'xtgl', '系统管理', '', '1', 'smgui', '99', '0', '0', '1', '0');
INSERT INTO `tp_menu` VALUES ('2', 'dygl', '导游管理', '', '1', '', '99', '0', '0', '1', '0');
INSERT INTO `tp_menu` VALUES ('3', 'wzgl', '文章管理', '', '1', '', '99', '0', '0', '1', '0');
INSERT INTO `tp_menu` VALUES ('4', '/admin/admins/index', '管理员管理', '', '1', '', '99', '1', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('5', '/admin/role/index', '角色管理', '', '1', '', '99', '1', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('6', '/admin/menu/index', '菜单管理', '', '1', '', '99', '1', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('7', '/admin/guide/index', '导游列表', '', '1', '', '99', '2', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('8', '/admin/user/index', '导游账号', '', '1', '', '99', '2', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('9', '/admin/article/index', '文章列表', '', '1', '', '99', '3', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('10', '/admin/admins/add', '添加', '', '1', '', '99', '4', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('11', '/admin/admins/edit', '修改', '', '1', '', '99', '4', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('12', '/admin/admins/delete', '删除', '', '1', '', '99', '4', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('13', '/admin/menu/add', '添加', '', '1', '', '99', '6', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('14', '/admin/menu/edit', '修改', '', '1', '', '99', '6', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('15', '/admin/menu/delete', '删除', '', '1', '', '99', '6', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('16', '/admin/access/authorize', '权限设置', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('17', '/admin/role/add', '添加', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('18', '/admin/role/edit', '修改', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('19', '/admin/role/delete', '删除', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('20', '/admin/guide/add', '添加', '', '1', '', '99', '7', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('21', '/admin/guide/edit', '编辑', '', '1', '', '99', '7', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('22', '/admin/guide/del', '删除', '', '1', '', '99', '7', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('23', '/admin/article/add', '添加', '', '1', '', '99', '9', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('24', '/admin/article/edit', '编辑', '', '1', '', '99', '9', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('25', '/admin/article/delete', '删除', '', '1', '', '99', '9', '2', '0', '1');

-- ----------------------------
-- Table structure for tp_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除 1是 0否',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `isdelete` (`isdelete`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_role
-- ----------------------------
INSERT INTO `tp_role` VALUES ('1', '超级管理员', '1518060556', '1518060556', '0', '1', ' 发反倒是反对方答');
INSERT INTO `tp_role` VALUES ('2', '超级2', '1518060549', '1518060549', '0', '1', ' dfdfdsfff');
INSERT INTO `tp_role` VALUES ('7', '545454', '1518164378', '0', '0', '1', '54545454');
INSERT INTO `tp_role` VALUES ('8', '767676', '1518164438', '0', '0', '1', '767676');
INSERT INTO `tp_role` VALUES ('9', '43434', '1518164497', '1518164684', '0', '1', '432434反对方答复');
