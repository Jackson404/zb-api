/*
 Navicat Premium Data Transfer

 Source Server         : rds
 Source Server Type    : MySQL
 Source Server Version : 80016
 Source Host           : zhengbu123.mysql.rds.aliyuncs.com:3306
 Source Schema         : zhengbu

 Target Server Type    : MySQL
 Target Server Version : 80016
 File Encoding         : 65001

 Date: 17/09/2019 15:46:58
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for zb_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `zb_admin_user`;
CREATE TABLE `zb_admin_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '后台用户名字',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '后台用户密码',
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '1  2ep',
  `isDelete` tinyint(1) NULL DEFAULT 0 COMMENT '删除标记',
  `createTime` datetime(0) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_admin_user_login_history
-- ----------------------------
DROP TABLE IF EXISTS `zb_admin_user_login_history`;
CREATE TABLE `zb_admin_user_login_history`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loginName` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '后台用户登录名字',
  `userId` int(11) NOT NULL COMMENT '后台用户id',
  `token` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'token',
  `loginIp` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录ip地址',
  `loginTime` datetime(0) NULL DEFAULT NULL COMMENT '登录时间',
  `loginOut` int(11) NULL DEFAULT 0 COMMENT '登出',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 333 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_area
-- ----------------------------
DROP TABLE IF EXISTS `zb_area`;
CREATE TABLE `zb_area`  (
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `area` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `town` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE = InnoDB AUTO_INCREMENT = 46003 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_company_management
-- ----------------------------
DROP TABLE IF EXISTS `zb_company_management`;
CREATE TABLE `zb_company_management`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `industryId` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '公司名字',
  `province` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '省份名',
  `city` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '城市名',
  `area` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '区名',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '详细地址',
  `contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `wxNumber` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `leader` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '负责人',
  `nature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '公司性质',
  `profile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '公司简介',
  `positionCount` int(11) NOT NULL DEFAULT 0 COMMENT '职位数量',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `isCert` int(11) NOT NULL DEFAULT 0 COMMENT '0 未认证  1认证过',
  `dataBank` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '[]' COMMENT '公司资料库',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0 COMMENT '标记删除',
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_cert
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_cert`;
CREATE TABLE `zb_enterprise_cert`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `epId` int(11) NOT NULL COMMENT '企业id',
  `reviewCertId` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '认证类型 1企业 2个人',
  `isDelete` int(11) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_cert_review
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_cert_review`;
CREATE TABLE `zb_enterprise_cert_review`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `applyEpId` int(11) NOT NULL DEFAULT 0,
  `groupId` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL,
  `realname` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `realphone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `idCard` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '身份证id',
  `idCardFrontPic` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `idCardBackPic` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `companyName` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `companyAddr` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `businessLic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `otherQuaLic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pass` tinyint(4) NULL DEFAULT 0 COMMENT ' -1 未通过 0 在审核 1 通过',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NOT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_em_group
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_em_group`;
CREATE TABLE `zb_enterprise_em_group`  (
  `groupId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `epUserId` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `isDelete` int(11) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`groupId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_msg
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_msg`;
CREATE TABLE `zb_enterprise_msg`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sendUsername` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `sendUserId` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `recUsername` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `recUserId` int(11) NOT NULL,
  `isDelete` int(11) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_order
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_order`;
CREATE TABLE `zb_enterprise_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `orderId` bigint(20) NOT NULL COMMENT '订单号',
  `userId` int(11) NOT NULL COMMENT '接单用户id',
  `epId` int(11) NOT NULL DEFAULT 0 COMMENT '公司id',
  `positionId` int(11) NOT NULL COMMENT '职位id',
  `qrCode` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `income` int(11) NOT NULL DEFAULT 0 COMMENT '收益',
  `entryNum` int(11) NOT NULL DEFAULT 0 COMMENT '入职数量',
  `interviewNum` int(11) NOT NULL DEFAULT 0 COMMENT '面试数量',
  `applyNum` int(11) NOT NULL DEFAULT 0 COMMENT '申请数量',
  `recOrderYear` int(20) NOT NULL COMMENT '接单时间年份',
  `recOrderMonth` int(11) NOT NULL COMMENT '接单时间月份',
  `isDelete` int(11) NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_order_apply
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_order_apply`;
CREATE TABLE `zb_enterprise_order_apply`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `orderId` bigint(20) NOT NULL COMMENT '订单id',
  `resumeId` int(11) NOT NULL,
  `shareUserId` int(11) NOT NULL COMMENT '分享的用户id',
  `applyUserId` int(11) NOT NULL,
  `interviewStatus` int(11) NOT NULL DEFAULT 0 COMMENT '1  已面试  0 未面试',
  `entryStatus` int(11) NOT NULL DEFAULT 0 COMMENT '1 已入职 0未入职',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_resume
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_resume`;
CREATE TABLE `zb_enterprise_resume`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `resumeId` int(11) NOT NULL COMMENT '简历id',
  `resumeCateId` int(11) NOT NULL DEFAULT 0 COMMENT '简历分类id',
  `idCard` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '来源是2得时候',
  `phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '来源是2得时候',
  `source` int(11) NOT NULL DEFAULT 0 COMMENT '1申请 2下载',
  `isDelete` int(11) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 98 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_resume_cate
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_resume_cate`;
CREATE TABLE `zb_enterprise_resume_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(125) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '简历分类名字',
  `isDelete` int(11) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_user
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_user`;
CREATE TABLE `zb_enterprise_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `realname` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `orderNum` int(11) NOT NULL COMMENT '总订单量',
  `incomeTotal` int(11) NOT NULL DEFAULT 0 COMMENT '总收益',
  `epId` int(11) NOT NULL DEFAULT 0 COMMENT '公司id',
  `isReview` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '1 企业 2个人',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_enterprise_user_login_history
-- ----------------------------
DROP TABLE IF EXISTS `zb_enterprise_user_login_history`;
CREATE TABLE `zb_enterprise_user_login_history`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loginName` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '后台用户登录名字',
  `loginPhone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `userId` int(11) NOT NULL COMMENT '后台用户id',
  `token` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'token',
  `loginIp` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录ip地址',
  `loginTime` datetime(0) NULL DEFAULT NULL COMMENT '登录时间',
  `loginOut` int(11) NULL DEFAULT 0 COMMENT '登出',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 282 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_industry
-- ----------------------------
DROP TABLE IF EXISTS `zb_industry`;
CREATE TABLE `zb_industry`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '行业代码',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '行业名称',
  `pid` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '父级代码',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '行业等级',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `industry_id`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1623 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '所属国民经济行业分类' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_label_management
-- ----------------------------
DROP TABLE IF EXISTS `zb_label_management`;
CREATE TABLE `zb_label_management`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签名',
  `isDelete` tinyint(1) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_news
-- ----------------------------
DROP TABLE IF EXISTS `zb_news`;
CREATE TABLE `zb_news`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) NOT NULL COMMENT '新闻分类id',
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `imgUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `isShow` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否展示',
  `isHot` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否热门',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否删除',
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 152 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_news_category
-- ----------------------------
DROP TABLE IF EXISTS `zb_news_category`;
CREATE TABLE `zb_news_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '新闻分类名字',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_position_cate
-- ----------------------------
DROP TABLE IF EXISTS `zb_position_cate`;
CREATE TABLE `zb_position_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pid` int(11) NULL DEFAULT NULL,
  `isDelete` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 975 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_position_management
-- ----------------------------
DROP TABLE IF EXISTS `zb_position_management`;
CREATE TABLE `zb_position_management`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `positionCateId` int(11) NOT NULL COMMENT '职位分类id',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '职位名字',
  `companyId` int(11) NOT NULL COMMENT '公司id',
  `minPay` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最少薪资',
  `maxPay` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最大薪资',
  `pay` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '薪资',
  `minWorkExp` int(11) NOT NULL DEFAULT 0 COMMENT '最少工作经验',
  `maxWorkExp` int(11) NOT NULL COMMENT '最大工作经验',
  `workExp` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '工作经验',
  `education` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '学历',
  `age` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '年龄',
  `num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '招聘数量',
  `labelIds` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '[]' COMMENT '标签内容',
  `isSoldierPriority` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否军人优先',
  `positionRequirement` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '岗位职责',
  `isShow` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否展示',
  `isHot` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否热门',
  `applyCount` int(11) NOT NULL DEFAULT 0 COMMENT '简历申请数量',
  `interviewAddress` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '面试地点',
  `interviewTime` bigint(20) NOT NULL DEFAULT 0 COMMENT '面试时间\r\n',
  `endTime` bigint(20) NOT NULL DEFAULT 0,
  `unitPrice` int(11) NOT NULL DEFAULT 0 COMMENT '人员单价',
  `positionType` int(11) NOT NULL DEFAULT 1 COMMENT '1c  2订单',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 97 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_resume
-- ----------------------------
DROP TABLE IF EXISTS `zb_resume`;
CREATE TABLE `zb_resume`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL COMMENT '用户id',
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `gender` int(11) NOT NULL DEFAULT 0 COMMENT '性别',
  `age` int(10) UNSIGNED NOT NULL COMMENT '年龄',
  `workYear` int(11) NOT NULL COMMENT '工作年限',
  `education` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '学历',
  `skills` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '技能',
  `selfEvaluation` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '自我评价',
  `exPosition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '期望职位',
  `salary` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '期望薪资',
  `nature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '工作性质',
  `exCity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '期望城市',
  `curStatus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '目前状况',
  `arrivalTime` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '到岗时间',
  `isSoldierPriority` int(11) NOT NULL DEFAULT 0 COMMENT '是否是退役军人',
  `militaryTime` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '入伍时间',
  `attendedTime` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '服役时长',
  `corps` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '兵种',
  `isOpen` int(11) NOT NULL DEFAULT 1 COMMENT '1 公开  0私密',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  `habitation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_slide_show
-- ----------------------------
DROP TABLE IF EXISTS `zb_slide_show`;
CREATE TABLE `zb_slide_show`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `imgUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片地址',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `turnUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转地址',
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '1 轮播图 2广告位',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序 值越大 desc',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_user
-- ----------------------------
DROP TABLE IF EXISTS `zb_user`;
CREATE TABLE `zb_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `avatar` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '手机号',
  `password` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `mini_openid` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '小程序openid',
  `wx_openid` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信openId',
  `unionId` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 88 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_user_apply_position
-- ----------------------------
DROP TABLE IF EXISTS `zb_user_apply_position`;
CREATE TABLE `zb_user_apply_position`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `positionId` int(11) NOT NULL COMMENT '职位id',
  `resumeId` int(11) NOT NULL COMMENT '简历id',
  `userId` int(11) NOT NULL COMMENT '用户id',
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `createBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_user_browsing_history
-- ----------------------------
DROP TABLE IF EXISTS `zb_user_browsing_history`;
CREATE TABLE `zb_user_browsing_history`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `isDelete` tinyint(4) NOT NULL DEFAULT 0,
  `createBy` int(11) NOT NULL,
  `createTime` datetime(0) NULL DEFAULT NULL,
  `updateBy` int(11) NULL DEFAULT NULL,
  `updateTime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zb_user_login_history
-- ----------------------------
DROP TABLE IF EXISTS `zb_user_login_history`;
CREATE TABLE `zb_user_login_history`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loginPhone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录手机号',
  `loginName` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录名字',
  `userId` int(11) NOT NULL COMMENT '用户id',
  `token` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户token',
  `loginIp` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录ip',
  `loginTime` datetime(0) NULL DEFAULT NULL COMMENT '登录时间',
  `loginOut` int(255) NOT NULL DEFAULT 0 COMMENT '登出 ',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 597 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
