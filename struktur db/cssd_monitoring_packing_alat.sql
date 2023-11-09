/*
 Navicat Premium Data Transfer

 Source Server         : SERVER_07
 Source Server Type    : MySQL
 Source Server Version : 100417
 Source Host           : 192.168.30.24:3306
 Source Schema         : sik_

 Target Server Type    : MySQL
 Target Server Version : 100417
 File Encoding         : 65001

 Date: 09/10/2023 08:50:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_packing_alat
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_packing_alat`;
CREATE TABLE `cssd_monitoring_packing_alat`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `tanggal_packing` date NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_packing_alat
-- ----------------------------
INSERT INTO `cssd_monitoring_packing_alat` VALUES ('b72651cd-5bc7-4d14-af94-22105f9f9219', '2023-08-07', '2023-08-07 10:47:32', '2023-08-07 10:47:32', NULL);

SET FOREIGN_KEY_CHECKS = 1;
