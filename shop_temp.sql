/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.42
Source Server Version : 50718
Source Host           : 192.168.1.42:3306
Source Database       : shop_temp

Target Server Type    : MYSQL
Target Server Version : 50718
File Encoding         : 65001

Date: 2018-06-13 15:14:49
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
  `pro_model1` text COLLATE utf8_unicode_ci COMMENT '适用车型',
  `pro_model2` text COLLATE utf8_unicode_ci COMMENT '适用车型语言2',
  `pro_model3` text COLLATE utf8_unicode_ci COMMENT '适用车型语言3',
  `pro_price` decimal(12,2) DEFAULT '0.00',
  `pro_check_json` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '{u_id, u_name, time, remark}',
  `pro_atime` int(11) DEFAULT NULL COMMENT '添加时间',
  `pro_etime` int(11) DEFAULT NULL COMMENT '最后编辑时间',
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sh_product
-- ----------------------------
INSERT INTO `sh_product` VALUES ('15', '1', '8', '刹车片', 'Brake Pad Set', 'тормозные колодки комплекс', '雷诺', ' Renault ', ' Renault ', '大众 运输者四代箱式车 (70XA) ;大众 运输者四代巴士(70XB,70XC,7DB,7DW) ;大众 运输者四代卡车(70XD)', 'Mass transporter four generation box car (70XA); mass transporter four generation bus (70XB, 70XC, 7DB, 7DW); mass transporter four generation truck (70XD)', 'Четыре поколения  камерные  автомобиль  Volkswagen  перевозчиков  (  70XA  )  ;  четыре поколения  автобус  общественного транспорта  (  70XB  лиц  ,  70XC  ,  7DB  ,  7DW  )  ;  четыре поколения  Volkswagen  перевозчиков  (  70XD  грузовик  ) ', '88.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528793470', '1528793484');
INSERT INTO `sh_product` VALUES ('16', '1', '8', '刹车盘', 'Brake disc', 'тормозной диск', '大众', 'toyata', 'общественности', '大众 TRANSPORTER IV Box (70XA)\r\n大众 TRANSPORTER IV Bus (70XB, 70XC, 7DB, 7DW)\r\n大众 TRANSPORTER IV Platform/Chassis (70XD)', 'Volkswagen  TRANSPORTER IV Box (70XA)\r\nVolkswagen TRANSPORTER IV Bus (70XB, 70XC, 7DB, 7DW)\r\nVolkswagen  TRANSPORTER IV Platform/Chassis (70XD)', 'фольксваген - транспортер IV коробку (70xa)\r\nфольксваген - транспортер IV автобус (70xb, 70xc, 7db, 7dw)\r\nфольксваген - транспортер IV платформы / шасси (70xd)', '98.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528793709', '1528793744');
INSERT INTO `sh_product` VALUES ('17', '1', '8', '刹车片组', 'Brake Pad Set', 'тормозные колодки комплекс', '沃尔沃(富豪)', 'Volvo (rich)', 'Volvo', '雪铁龙 C4 Picasso II\r\n雪铁龙 C4 Grand Picasso II\r\n标致 308 SW II\r\n标致 308 II', 'Citroen C4 Picasso II\r\nCitroen C4 Grand Picasso II\r\nBeautiful 308 SW II\r\nBeautiful 308 II', 'Citroen C4 Picasso II\r\nCitroen C4 Picasso второй\r\nкрасивая 308 SW II\r\nкрасивая 308 II', '89.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528793997', '1528794003');
INSERT INTO `sh_product` VALUES ('18', '1', '1', '刹车蹄片', 'Brake Shoe Set', 'тормозной башмак ', '现代', 'HYUNDAI', 'Hyundai', '现代圣达菲2013', 'HYUNDAI Santa Fe  2013-', 'Hyundai Santa Fe 2013 года -', '89.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528794242', '1528800677');
INSERT INTO `sh_product` VALUES ('19', '1', '12', '摩托车衬片', 'Motorcycle lining', 'мотоцикл  накладок ', '直口雅马哈', 'Straight mouth YAMAHA', 'прямой  экспорт  yamaha', '直口雅马哈125', 'Straight mouth YAMAHA 125', 'прямой  экспорт  yamaha  125 ', '99.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528794873', null);
INSERT INTO `sh_product` VALUES ('20', '0', '1', '摩托车盘式片', 'Brake Pads', 'мотоцикл  дискового типа  таблетки ', 'MOTORCYCLE', '摩托车', 'мотоцикл', 'MOTORCYCLE', '摩托车', 'мотоцикл', '99.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528795052', '1528866620');
INSERT INTO `sh_product` VALUES ('21', '1', '8', '刹车蹄片', 'Brake Shoe', 'тормозная колодка', '丰田', 'toyata', 'toyata', '丰田 克雷西达四门轿车(RX3_) 		07/77 - 07/81 \r\n丰田 克雷西达旅行车(RX3_) 		12/77 - 03/81 \r\n丰田 克雷西达旅行车(X6K) 		06/81 - 03/85 \r\n丰田 莱特艾斯箱式车(CM3_V, KM3_V) 		08/88 - 01/92', 'TOYOTA seic hatchback / hatchback vehicle (TA60, RA40, RA6_) 02/78 - 08/83\r\nTOYOTA TA4C (TA60) 04/80 - 02/8', ' Toyota  赛利卡  Седан  (  та2  )  10/75  -  07/77 \r\n 赛利卡  хэтчбек  /  хэтчбек  Toyota  (  TA60  ,  RA40  ,  RA6_  )  02/78  -  08/83 \r\n Эстер  (  M2_G  )  автобус  Toyota  Райт ', '89.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528795472', '1528798027');
INSERT INTO `sh_product` VALUES ('22', '1', '12', '刹车蹄片 04495-14010', 'Brake Shoe 04495-14010', '04495-14010 колодки', '奔驰', 'benchi', 'Mercedes - Benz', '大发 查门特四门轿车 		05/86 - 07/87 \r\n丰田 卡力那四门轿车(TA1_) 		10/75 - 03/78 \r\n丰田 卡力那四门轿车(TA4L, TA6_L', '05/86 - 07/87, the four door saloon car\r\nTOYOTA Klima four door sedan (TA1_) 10/75 - 03/78\r\nThe TOYOTA Kali four door sedan (TA4L, TA6_L)', 'Дафа  查门特  седан  05/86  -  07/87 \r\n Toyota  卡力  это  седан  (  TA1_  )  10/75  -  03/78 \r\n Toyota  卡力  это  седан  (  TA4L  ,  TA6_L ', '88.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528795608', null);
INSERT INTO `sh_product` VALUES ('23', '1', '8', '刹车片 06430-SFE-000', 'Brake Pad Set 06430-SFE-000', 'Brake pad set 06430 - SFE - 000', '奥迪', 'Audi', 'Audi', '奥迪 100四门轿车(43,C2) 		03/77 - 07/82 \r\n奥迪 100四门轿车(44,44Q,C3) 		08/89 - 07/91 \r\n奥迪 100旅行车(43,C2)', 'Audi 100 four door sedan (43, C2) 03/77 - 07/82\r\nAudi 100 four door sedan (44,44Q, C3) 08/89 - 07/91\r\nAudi 100 wagon (43, C2)', 'Audi  100  Седан  (  43  ,  03/77  -  07/82  C2  ) \r\n (  44,44Q  седан  Audi  100  ,  08/89  -  07/91  C3  ) \r\n Audi  100  универсал  (  43  ,  C2  ) ', '99.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528795758', null);
INSERT INTO `sh_product` VALUES ('24', '1', '1', '大车用鼓式刹车片', 'Drum brakes for big cars', 'тележка  с  барабанного  тормоза ', '豪沃', 'HOWO', ' хао ', '车及巴士', 'Car and bus', 'автомобиль  и  автобус ', '99.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8d85\\u7ea7\\u7ba1\\u7406\\u5458\",\"time\":1528867258,\"remark\":\"\\u7ba1\\u7406\\u5458\\u7f16\\u8f91\\u4ea7\\u54c1\"}', '1528796008', '1528867258');
INSERT INTO `sh_product` VALUES ('25', '1', '1', '摩托车衬片', 'Motorcycle lining', 'мотоцикл  накладок ', '雅马哈', 'YAMAHA', 'Yamaha ', '雅马哈 CD185, CD195', 'YAMAHA CD185, CD195', 'Yamaha CD185, CD195', '89.00', '{\"u_id\":\"1\",\"u_name\":\"\\u8bb8\\u4f1f\\u5f3a\",\"time\":1528861915,\"remark\":\"\\u5141\\u8bb8\\u53d1\\u5e03\"}', '1528796157', '1528802247');
INSERT INTO `sh_product` VALUES ('26', '1', '1', '三阳125后', 'After three yang and 125', 'После  саньян  125 ', '华日制动', 'Chinese and Japanese braking', 'Уари  тормозной ', '华日 BS125', 'Chinese and Japanese braking bs125', 'Уари  тормозной BS125', '123.12', '{\"u_id\":\"1\",\"u_name\":\"\\u8d85\\u7ea7\\u7ba1\\u7406\\u5458\",\"time\":1528872094,\"remark\":\"\\u7ba1\\u7406\\u5458\\u7f16\\u8f91\\u4ea7\\u54c1\"}', '1528796314', '1528872094');

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sh_product_number
-- ----------------------------
INSERT INTO `sh_product_number` VALUES ('13', '16', 'ATE', '7D0 698 151 A', 0x37443036393831353141, '0');
INSERT INTO `sh_product_number` VALUES ('14', '17', 'hava', '31408076', 0x3331343038303736, '0');
INSERT INTO `sh_product_number` VALUES ('15', '18', 'HYUNDAI', '58305-2WA00', 0x35383330353257413030, '0');
INSERT INTO `sh_product_number` VALUES ('16', '19', '华日编号', 'VT250', 0x5654323530, '1');
INSERT INTO `sh_product_number` VALUES ('17', '20', '华日编号', 'MT01', 0x4D543031, '0');
INSERT INTO `sh_product_number` VALUES ('18', '21', '华日编号', 'HRK2232', 0x48524B32323332, '1');
INSERT INTO `sh_product_number` VALUES ('19', '22', '华日编号', 'HR1702', 0x485231373032, '0');
INSERT INTO `sh_product_number` VALUES ('20', '23', '华日编号', 'HR1096-3', 0x48523130393633, '1');
INSERT INTO `sh_product_number` VALUES ('21', '24', '华日编号', '09款豪沃后', 0x3039, '0');
INSERT INTO `sh_product_number` VALUES ('22', '25', '华日编号', 'VT250', 0x5654323530, '1');
INSERT INTO `sh_product_number` VALUES ('23', '26', '华日编号', '三阳125后', 0x313235, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sh_product_pic
-- ----------------------------
INSERT INTO `sh_product_pic` VALUES ('14', '15', 'pro/358/', '5b1f89293985d', 'jpg', '14', 'E37408E3');
INSERT INTO `sh_product_pic` VALUES ('15', '16', 'pro/488/', '5b1f89f5d8246', 'jpg', '15', 'E37408E3');
INSERT INTO `sh_product_pic` VALUES ('16', '17', 'pro/281/', '5b1f8b076ba84', 'jpg', '16', '64CA9D28');
INSERT INTO `sh_product_pic` VALUES ('17', '18', 'pro/30/', '5b1f8c01c8825', 'jpg', '17', 'B79A1D5C');
INSERT INTO `sh_product_pic` VALUES ('18', '19', 'pro/113/', '5b1f8eac5bfb4', 'jpg', '0', 'CCA65352');
INSERT INTO `sh_product_pic` VALUES ('19', '20', 'pro/312/', '5b1f8f240ebbb', 'jpg', '19', 'D66DA05A');
INSERT INTO `sh_product_pic` VALUES ('20', '21', 'pro/125/', '5b1f912666f70', 'jpg', '0', '657BC961');
INSERT INTO `sh_product_pic` VALUES ('21', '22', 'pro/232/', '5b1f919e0e21a', 'jpg', '0', '3FEE0B73');
INSERT INTO `sh_product_pic` VALUES ('22', '23', 'pro/458/', '5b1f920e34f08', 'jpg', '0', 'F1DEE4D3');
INSERT INTO `sh_product_pic` VALUES ('23', '24', 'pro/196/', '5b1f93037b250', 'jpg', '23', 'D8594F30');
INSERT INTO `sh_product_pic` VALUES ('27', '26', 'pro/389/', '5b1fa58cb3298', 'jpg', '27', 'E37408E3');
INSERT INTO `sh_product_pic` VALUES ('29', '25', 'pro/129/', '5b1fabc605dab', 'jpg', '0', 'BD1EC4D8');
INSERT INTO `sh_product_pic` VALUES ('32', '20', 'pro/439/', '5b20a739dd715', 'jpg', '0', 'D8594F30');
INSERT INTO `sh_product_pic` VALUES ('33', '24', 'pro/1/', '5b20a86fee77c', 'jpg', '33', '59B42235');

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sh_user
-- ----------------------------
INSERT INTO `sh_user` VALUES ('1', '1', '1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', '18826233665', '020-31280636', 'xwq4358618513', '4358618514', '广州宜配信息科技有限公司', '1528367943', '1528367943');
INSERT INTO `sh_user` VALUES ('8', '2', '1', 'gongchang', 'e10adc3949ba59abbe56e057f20f883e', '苏小姐', '16688886666', '0593-6388368', 'mrhuari', '666888', '福建华日汽车配件有限公司', '1528436632', '1528436632');
INSERT INTO `sh_user` VALUES ('9', '0', '1', 'kehu', 'e10adc3949ba59abbe56e057f20f883e', '陈先生', '18899731335', '020-37368415', '', '', '广州京德贸易有限公司', '1528679034', '1528679034');
INSERT INTO `sh_user` VALUES ('10', '1', '1', 'guanliyuan', 'e10adc3949ba59abbe56e057f20f883e', '李经理', '18826233660', '020-31280636', '', '', '广州宜配信息科技有限公司', '1528766356', '1528766356');
INSERT INTO `sh_user` VALUES ('11', '0', '1', 'kehu2', 'e10adc3949ba59abbe56e057f20f883e', '张晓敏', '13625760087', '', '', '', '台州易宏实业有限公司', '1528771721', '1528771721');
INSERT INTO `sh_user` VALUES ('12', '2', '1', 'gongchang2', 'e10adc3949ba59abbe56e057f20f883e', '李晓红', '13625760087', null, null, null, '菲尔商贸有限公司', '1528771721', '1528771721');
