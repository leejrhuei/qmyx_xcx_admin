/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : qmyx_xcx

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-02 10:02:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `account` varchar(60) NOT NULL COMMENT '账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `scope` tinyint(1) DEFAULT '16' COMMENT '权限：32超管，16普管',
  `scope_description` varchar(255) DEFAULT NULL COMMENT '权限描述',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='后台用户表';

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'linxiao', '$2y$10$484sgvLs/6ol6WhzYcnTTuO2XU60/6ZOo5Q1mmZe7aj.i5AbYv/gC', '32', 'Super', '1531819473', '1531819473', null);

-- ----------------------------
-- Table structure for `banner`
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'Banner名称，通常作为标识',
  `description` varchar(255) DEFAULT NULL COMMENT 'Banner描述',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='banner管理表';

-- ----------------------------
-- Records of banner
-- ----------------------------
INSERT INTO `banner` VALUES ('1', '首页置顶', '首页轮播图', null, null);

-- ----------------------------
-- Table structure for `banner_item`
-- ----------------------------
DROP TABLE IF EXISTS `banner_item`;
CREATE TABLE `banner_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img_id` int(10) unsigned NOT NULL COMMENT '外键，关联image表',
  `key_word` varchar(100) NOT NULL COMMENT '执行关键字，根据不同的type含义不同',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '跳转类型，可能导向商品，可能导向专题，可能导向其他。0，无导向；1：导向商品;2:导向专题',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `banner_id` int(10) unsigned NOT NULL COMMENT '外键，关联banner表',
  `update_time` int(10) unsigned DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='banner子项表';

-- ----------------------------
-- Records of banner_item
-- ----------------------------

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `topic_img_id` int(10) unsigned DEFAULT NULL COMMENT '外键，关联image表',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL COMMENT '描述',
  `update_time` int(10) unsigned DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='商品类目';

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '分类1', '4', null, '分类1', '1531897354', '1531897070');

-- ----------------------------
-- Table structure for `image`
-- ----------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL COMMENT '图片路径',
  `from` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 来自本地，2 来自公网',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='图片总表';

-- ----------------------------
-- Records of image
-- ----------------------------

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `user_id` int(10) unsigned NOT NULL COMMENT '外键，用户id，注意并不是openid',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL,
  `total_price` decimal(6,2) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1:未支付， 2：已支付，3：已发货 , 4: 已支付，但库存不足',
  `snap_img` varchar(255) DEFAULT NULL COMMENT '订单快照图片',
  `snap_name` varchar(80) DEFAULT NULL COMMENT '订单快照名称',
  `total_count` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  `snap_items` text COMMENT '订单其他信息快照（json)',
  `snap_address` varchar(500) DEFAULT NULL COMMENT '地址快照',
  `prepay_id` varchar(100) DEFAULT NULL COMMENT '订单微信支付的预订单id（用于发送模板消息）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for `order_product`
-- ----------------------------
DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product` (
  `order_id` int(11) NOT NULL COMMENT '联合主键，订单id',
  `product_id` int(11) NOT NULL COMMENT '联合主键，商品id',
  `count` int(11) NOT NULL COMMENT '商品数量',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of order_product
-- ----------------------------

-- ----------------------------
-- Table structure for `product`
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL COMMENT '商品名称',
  `price` decimal(6,2) unsigned NOT NULL COMMENT '价格,单位：元',
  `stock` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存量',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `main_img_url` varchar(255) DEFAULT NULL COMMENT '主图ID号，这是一个反范式设计，有一定的冗余',
  `from` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '图片来自 1 本地 ，2公网',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL,
  `img_id` int(10) unsigned DEFAULT NULL COMMENT '图片外键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of product
-- ----------------------------

-- ----------------------------
-- Table structure for `product_image`
-- ----------------------------
DROP TABLE IF EXISTS `product_image`;
CREATE TABLE `product_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img_id` int(10) unsigned NOT NULL COMMENT '外键，关联图片表',
  `delete_time` int(10) unsigned DEFAULT NULL COMMENT '状态，主要表示是否删除，也可以扩展其他状态',
  `order` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片排序序号',
  `product_id` int(10) unsigned NOT NULL COMMENT '商品id，外键',
  `create_time` int(10) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of product_image
-- ----------------------------

-- ----------------------------
-- Table structure for `product_property`
-- ----------------------------
DROP TABLE IF EXISTS `product_property`;
CREATE TABLE `product_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '详情属性名称',
  `detail` varchar(255) NOT NULL COMMENT '详情属性',
  `product_id` int(10) unsigned NOT NULL COMMENT '商品id，外键',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of product_property
-- ----------------------------

-- ----------------------------
-- Table structure for `theme`
-- ----------------------------
DROP TABLE IF EXISTS `theme`;
CREATE TABLE `theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '专题名称',
  `description` varchar(255) DEFAULT NULL COMMENT '专题描述',
  `topic_img_id` int(11) NOT NULL COMMENT '主题图，外键',
  `delete_time` int(11) DEFAULT NULL,
  `head_img_id` int(11) NOT NULL COMMENT '专题列表页，头图',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='主题信息表';

-- ----------------------------
-- Records of theme
-- ----------------------------

-- ----------------------------
-- Table structure for `theme_product`
-- ----------------------------
DROP TABLE IF EXISTS `theme_product`;
CREATE TABLE `theme_product` (
  `theme_id` int(11) NOT NULL COMMENT '主题外键',
  `product_id` int(11) NOT NULL COMMENT '商品外键',
  PRIMARY KEY (`theme_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='主题所包含的商品';

-- ----------------------------
-- Records of theme_product
-- ----------------------------

-- ----------------------------
-- Table structure for `third_app`
-- ----------------------------
DROP TABLE IF EXISTS `third_app`;
CREATE TABLE `third_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(64) NOT NULL COMMENT '应用app_id',
  `app_secret` varchar(64) NOT NULL COMMENT '应用secret',
  `app_description` varchar(100) DEFAULT NULL COMMENT '应用程序描述',
  `scope` varchar(20) NOT NULL COMMENT '应用权限',
  `scope_description` varchar(100) DEFAULT NULL COMMENT '权限描述',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='访问API的各应用账号密码表';

-- ----------------------------
-- Records of third_app
-- ----------------------------
INSERT INTO `third_app` VALUES ('1', 'starcraft', '777*777', 'CMS', '32', 'Super', null, null);

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `nickName` varchar(50) DEFAULT NULL,
  `gender` tinyint(1) unsigned NOT NULL,
  `avatarUrl` varchar(255) NOT NULL,
  `country` varchar(30) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `language` varchar(30) NOT NULL,
  `delete_time` int(10) unsigned DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '注册时间',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for `user_address`
-- ----------------------------
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE `user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '收获人姓名',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(20) DEFAULT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `country` varchar(20) DEFAULT NULL COMMENT '区',
  `detail` varchar(100) DEFAULT NULL COMMENT '详细地址',
  `delete_time` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT '外键',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user_address
-- ----------------------------
