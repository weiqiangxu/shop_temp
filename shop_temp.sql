/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.42
Source Server Version : 50718
Source Host           : 192.168.1.42:3306
Source Database       : shop_temp

Target Server Type    : MYSQL
Target Server Version : 50718
File Encoding         : 65001

Date: 2018-06-08 18:08:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sh_product
-- ----------------------------
DROP TABLE IF EXISTS `sh_product`;
CREATE TABLE `sh_product` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品ID',
  `pro_status` smallint(6) DEFAULT '0' COMMENT '0待审 1已审核 2审核不通过',
  `pro_u_id` int(11) DEFAULT NULL COMMENT '发布者',
  `pro_name1` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '产品名称',
  `pro_name2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '产品名称语言2',
  `pro_name3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '产品名称语言3',
  `pro_make1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '适用品牌',
  `pro_make2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '适用品牌语言2',
  `pro_make3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '适用品牌语言3',
  `pro_model1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '适用车型',
  `pro_model2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '适用车型语言2',
  `pro_model3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '适用车型语言3',
  `pro_price` decimal(12,2) DEFAULT '0.00',
  `pro_check_json` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '{u_id, u_name, time, remark}',
  `pro_atime` int(11) DEFAULT NULL COMMENT '添加时间',
  `pro_etime` int(11) DEFAULT NULL COMMENT '最后编辑时间',
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sh_product
-- ----------------------------
INSERT INTO `sh_product` VALUES ('8', '0', '8', '123', '', '', '123', '', '', '123', '', '', '123.00', null, '1528447230', null);
INSERT INTO `sh_product` VALUES ('9', '0', '8', '123', '', '', '123', '', '', '123', '', '', '123.00', null, '1528447284', null);

-- ----------------------------
-- Table structure for sh_product_number
-- ----------------------------
DROP TABLE IF EXISTS `sh_product_number`;
CREATE TABLE `sh_product_number` (
  `prn_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '号码ID',
  `prn_pro_id` int(11) NOT NULL COMMENT '产品ID',
  `prn_factory` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '厂商',
  `prn_display` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '原始号码',
  `prm_format` varbinary(255) NOT NULL COMMENT '格式化号码',
  `prm_ismain` smallint(6) DEFAULT NULL COMMENT '主号码',
  PRIMARY KEY (`prn_id`),
  KEY `idx_prm_format` (`prm_format`),
  KEY `idx_prn_pro_id` (`prn_pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sh_product_number
-- ----------------------------
INSERT INTO `sh_product_number` VALUES ('1', '9', '奔驰', '666', 0x363636, '0');

-- ----------------------------
-- Table structure for sh_product_pic
-- ----------------------------
DROP TABLE IF EXISTS `sh_product_pic`;
CREATE TABLE `sh_product_pic` (
  `prp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品图片 每个产品最多3张图 小图 100*100 800*800  原图',
  `prp_pro_id` int(11) NOT NULL COMMENT '产品ID',
  `prp_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '路径 pro/rand(1, 500)/',
  `prp_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `prp_ext` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '后缀',
  `prp_seq` int(255) DEFAULT '0',
  `prp_crc32` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`prp_id`),
  KEY `idx_prp_seq` (`prp_seq`),
  KEY `idx_prp_pro_id` (`prp_pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sh_product_pic
-- ----------------------------
INSERT INTO `sh_product_pic` VALUES ('3', '8', 'pro/399/', '5b1a40dc0b97f', 'jpg', '0', 'F4CEDF0D');
INSERT INTO `sh_product_pic` VALUES ('4', '9', 'pro/399/', '5b1a40dc0b97f', 'jpg', '0', 'F4CEDF0D');

-- ----------------------------
-- Table structure for sh_user
-- ----------------------------
DROP TABLE IF EXISTS `sh_user`;
CREATE TABLE `sh_user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员表',
  `u_style` smallint(6) DEFAULT '0' COMMENT '1管理员  2工厂 0客户',
  `u_status` int(11) DEFAULT '1' COMMENT '-1禁用  1正常',
  `u_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `u_password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `u_realname` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '姓名',
  `u_mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号码',
  `u_tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系电话 多个逗号分隔',
  `u_weixin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '微信号 多个逗号分隔',
  `u_qq` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'QQ 多个逗号分隔',
  `u_company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '公司名称',
  `u_reg_time` int(11) DEFAULT '0' COMMENT '注册时间',
  `u_login_time` int(11) DEFAULT '0' COMMENT '登陆时间',
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `uni_u_name` (`u_name`),
  KEY `idx_u_mobile` (`u_mobile`),
  KEY `idx_u_reg_time` (`u_reg_time`),
  KEY `idx_u_status` (`u_status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sh_user
-- ----------------------------
INSERT INTO `sh_user` VALUES ('1', '1', '1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '许伟强', '18826233660', '613660', 'xwq435861851', '435861851', '广州宜配', '1528367943', '1528367943');
INSERT INTO `sh_user` VALUES ('8', '2', '1', 'gongchang', 'e10adc3949ba59abbe56e057f20f883e', 'xuweiqiang', '18826233660', '613660', 'xwq435861851', '435861851', '工厂', '1528436632', '1528436632');
