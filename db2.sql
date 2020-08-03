/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : school_homework

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-08-03 18:41:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sh_admin
-- ----------------------------
DROP TABLE IF EXISTS `sh_admin`;
CREATE TABLE `sh_admin` (
  `pk_admin` char(32) NOT NULL,
  `fk_user` char(45) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  PRIMARY KEY (`pk_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_admin
-- ----------------------------
INSERT INTO `sh_admin` VALUES ('1', '1', '0', '0', 'able');
INSERT INTO `sh_admin` VALUES ('2', '2', '0', '0', 'able');

-- ----------------------------
-- Table structure for sh_classes
-- ----------------------------
DROP TABLE IF EXISTS `sh_classes`;
CREATE TABLE `sh_classes` (
  `pk_classes` char(32) NOT NULL,
  `class_name` varchar(255) DEFAULT NULL COMMENT '班级名称\n',
  `create_time` int(11) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  PRIMARY KEY (`pk_classes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_classes
-- ----------------------------
INSERT INTO `sh_classes` VALUES ('1', '表格收集', '0', 'able');

-- ----------------------------
-- Table structure for sh_homeworks
-- ----------------------------
DROP TABLE IF EXISTS `sh_homeworks`;
CREATE TABLE `sh_homeworks` (
  `pk_homeworks` char(32) NOT NULL,
  `fk_classes` char(32) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `auto_name_rule` varchar(255) DEFAULT NULL COMMENT '命名规则\n',
  `create_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL COMMENT '截止日期',
  `desc` varchar(255) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_homeworks`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_homeworks
-- ----------------------------
INSERT INTO `sh_homeworks` VALUES ('77351ba70a97ae7a0e90a4a3216be5a4', '1', '测试作业', 'ABC', '1596450328', '1598544000', 'sss', 'able', '1596450328');

-- ----------------------------
-- Table structure for sh_user
-- ----------------------------
DROP TABLE IF EXISTS `sh_user`;
CREATE TABLE `sh_user` (
  `pk_user` char(32) NOT NULL,
  `fk_class` char(32) DEFAULT NULL,
  `login_name` varchar(45) DEFAULT NULL,
  `login_passwork` varchar(45) DEFAULT NULL,
  `login_email` varchar(45) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `stu_no` varchar(45) DEFAULT NULL COMMENT '学号',
  PRIMARY KEY (`pk_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_user
-- ----------------------------
INSERT INTO `sh_user` VALUES ('1', '1', '20150104010225', '20150104010225', '1228746736@qq.com', '0', '0', 'able', '毛麟', '20150104010225');
INSERT INTO `sh_user` VALUES ('2', '1', '2019021964', '2019021964', '1228746736@qq.com', '0', '0', 'able', '孙武', '2019021964');

-- ----------------------------
-- Table structure for sh_works_items
-- ----------------------------
DROP TABLE IF EXISTS `sh_works_items`;
CREATE TABLE `sh_works_items` (
  `pk_works_items` char(32) NOT NULL,
  `fk_homeworks` char(32) DEFAULT NULL,
  `fk_user` char(32) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_works_items`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_works_items
-- ----------------------------
INSERT INTO `sh_works_items` VALUES ('f2b75734c2326f887f79dfd3c1ae9296', '77351ba70a97ae7a0e90a4a3216be5a4', '2', '2019021964-孙武-测试作业.txt', '1596450696', '1596450706');
