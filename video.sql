/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.110.232
 Source Server Type    : MySQL
 Source Server Version : 50529
 Source Host           : 192.168.110.232:3306
 Source Schema         : a_video

 Target Server Type    : MySQL
 Target Server Version : 50529
 File Encoding         : 65001

 Date: 03/05/2018 15:08:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_permission_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_permission_role`;
CREATE TABLE `admin_permission_role`  (
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_permission_role
-- ----------------------------
INSERT INTO `admin_permission_role` VALUES (2, 2);
INSERT INTO `admin_permission_role` VALUES (3, 2);
INSERT INTO `admin_permission_role` VALUES (4, 2);
INSERT INTO `admin_permission_role` VALUES (5, 2);
INSERT INTO `admin_permission_role` VALUES (6, 2);
INSERT INTO `admin_permission_role` VALUES (7, 2);
INSERT INTO `admin_permission_role` VALUES (8, 2);
INSERT INTO `admin_permission_role` VALUES (9, 2);
INSERT INTO `admin_permission_role` VALUES (10, 2);
INSERT INTO `admin_permission_role` VALUES (11, 2);
INSERT INTO `admin_permission_role` VALUES (12, 2);
INSERT INTO `admin_permission_role` VALUES (13, 2);
INSERT INTO `admin_permission_role` VALUES (15, 2);
INSERT INTO `admin_permission_role` VALUES (17, 2);
INSERT INTO `admin_permission_role` VALUES (18, 2);
INSERT INTO `admin_permission_role` VALUES (19, 2);

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '权限解释名称',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '描述与备注',
  `cid` tinyint(4) NOT NULL COMMENT '级别',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图标',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
INSERT INTO `admin_permissions` VALUES (1, 'admin.permission', '权限管理', '权限管理', 0, 'fa-users', '2016-05-21 10:06:50', '2016-06-22 13:49:09');
INSERT INTO `admin_permissions` VALUES (2, 'admin.permission.index', '权限列表', '', 1, '', '2016-05-21 10:08:04', '2016-05-21 10:08:04');
INSERT INTO `admin_permissions` VALUES (3, 'admin.permission.create', '权限添加', '', 1, '', '2016-05-21 10:08:18', '2016-05-21 10:08:18');
INSERT INTO `admin_permissions` VALUES (4, 'admin.permission.edit', '权限修改', '', 1, '', '2016-05-21 10:08:35', '2016-05-21 10:08:35');
INSERT INTO `admin_permissions` VALUES (5, 'admin.permission.destroy ', '权限删除', '', 1, '', '2016-05-21 10:09:57', '2016-05-21 10:09:57');
INSERT INTO `admin_permissions` VALUES (6, 'admin.role.index', '角色列表', '', 1, '', '2016-05-23 10:36:40', '2016-05-23 10:36:40');
INSERT INTO `admin_permissions` VALUES (7, 'admin.role.create', '角色添加', '', 1, '', '2016-05-23 10:37:07', '2016-05-23 10:37:07');
INSERT INTO `admin_permissions` VALUES (8, 'admin.role.edit', '角色修改', '', 1, '', '2016-05-23 10:37:22', '2016-05-23 10:37:22');
INSERT INTO `admin_permissions` VALUES (9, 'admin.role.destroy', '角色删除', '', 1, '', '2016-05-23 10:37:48', '2016-05-23 10:37:48');
INSERT INTO `admin_permissions` VALUES (10, 'admin.user.index', '用户管理', '', 1, '', '2016-05-23 10:38:52', '2016-05-23 10:38:52');
INSERT INTO `admin_permissions` VALUES (11, 'admin.user.create', '用户添加', '', 1, '', '2016-05-23 10:39:21', '2016-06-22 13:49:29');
INSERT INTO `admin_permissions` VALUES (12, 'admin.user.edit', '用户编辑', '', 1, '', '2016-05-23 10:39:52', '2016-05-23 10:39:52');
INSERT INTO `admin_permissions` VALUES (13, 'admin.user.destroy', '用户删除', '', 1, '', '2016-05-23 10:40:36', '2016-05-23 10:40:36');
INSERT INTO `admin_permissions` VALUES (14, 'admin.video', '视频管理', '视频管理', 0, 'fa-play', '2018-04-21 11:40:17', '2018-04-21 11:40:17');
INSERT INTO `admin_permissions` VALUES (15, 'admin.video.index', '视频列表', '视频列表', 14, '', '2018-04-21 11:42:01', '2018-04-21 11:42:01');
INSERT INTO `admin_permissions` VALUES (17, 'admin.video.create', '视频添加', '视频添加', 14, '', '2018-04-24 13:45:34', '2018-04-24 13:45:34');
INSERT INTO `admin_permissions` VALUES (18, 'admin.video.edit', '视频编辑', '视频编辑', 14, '', '2018-04-24 13:45:53', '2018-04-24 13:45:53');
INSERT INTO `admin_permissions` VALUES (19, 'admin.video.destory', '视频删除', '视频删除', 14, '', '2018-04-24 13:46:17', '2018-04-24 13:46:17');
INSERT INTO `admin_permissions` VALUES (20, 'admin.video.push', '视频预热缓存', '视频预热缓存', 14, '', '2018-05-03 15:06:48', '2018-05-03 15:07:09');
INSERT INTO `admin_permissions` VALUES (21, 'admin.video.refresh', '视频刷新缓存', '视频刷新缓存', 14, '', '2018-05-03 15:07:36', '2018-05-03 15:07:36');

-- ----------------------------
-- Table structure for admin_role_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_user`;
CREATE TABLE `admin_role_user`  (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_role_user
-- ----------------------------
INSERT INTO `admin_role_user` VALUES (2, 18);

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES (2, '超级管理员', '超级管理员', '2018-04-24 13:48:25', '2018-04-24 13:48:25');

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '电魂id',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '登录ip',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '上次登录时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES (1, '胡海楠', 'huhainan', '192.168.212.52', '2018-05-03 15:05:29', '2018-04-21 16:47:05', '2018-05-03 15:05:29');
INSERT INTO `admin_users` VALUES (18, '沈岑伟', 'shencenwei', '192.168.212.133', '2018-04-27 16:06:52', '2018-04-24 11:06:39', '2018-04-27 16:06:52');
INSERT INTO `admin_users` VALUES (19, '黄勤', 'coder', '192.168.212.7', '2018-04-27 16:05:19', '2018-04-27 16:05:19', '2018-04-27 16:05:19');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2016_11_10_033438_create_admin_users_table', 1);
INSERT INTO `migrations` VALUES (4, '2016_11_10_034922_create_admin_permissions_table', 1);
INSERT INTO `migrations` VALUES (5, '2016_11_10_034952_create_admin_roles_table', 1);
INSERT INTO `migrations` VALUES (6, '2016_11_10_035417_create_admin_role_user_table', 1);
INSERT INTO `migrations` VALUES (7, '2016_11_10_035534_create_admin_permission_role_table', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE,
  INDEX `password_resets_token_index`(`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for video
-- ----------------------------
DROP TABLE IF EXISTS `video`;
CREATE TABLE `video`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '视频标题',
  `youku_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '优酷id',
  `aliyun_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '阿里云id',
  `level` tinyint(4) NOT NULL DEFAULT 1 COMMENT '使用时间范围等级 1一直使用 2一个月内 3三个月内 4一年内 5永远不',
  `current_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '当前使用视频源  1 阿里云 2优酷',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_youku`(`youku_id`) USING BTREE,
  INDEX `index_aliyun`(`aliyun_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of video
-- ----------------------------
INSERT INTO `video` VALUES (6, '梦三国2：夜刃-吕轲宣传视频', 'XOTU2MDk1NTU2', 'fb3dd4c7fc0a483bbe4c4038e54d48df', 1, 1, '2018-04-24 13:56:35', '2018-04-24 13:56:35');

SET FOREIGN_KEY_CHECKS = 1;
