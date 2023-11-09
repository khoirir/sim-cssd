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

 Date: 09/10/2023 08:50:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_mesin_steam
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_mesin_steam`;
CREATE TABLE `cssd_monitoring_mesin_steam`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `tanggal_monitoring` datetime NOT NULL,
  `shift` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `siklus` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mesin` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proses_ulang` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_proses_ulang` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_mesin_steam
-- ----------------------------
INSERT INTO `cssd_monitoring_mesin_steam` VALUES ('04f0fc9a-7739-456d-9098-0dec2e119a12', '2023-09-21 09:36:00', 'pagi', '1', 'Getinge', NULL, NULL, '2023-09-21 11:37:10', '2023-09-21 11:37:10', NULL);
INSERT INTO `cssd_monitoring_mesin_steam` VALUES ('41f87dd7-0017-4eb6-a01d-533ca2526ccf', '2023-08-09 08:00:00', 'pagi', '1', 'Getinge', NULL, NULL, '2023-08-09 09:57:01', '2023-08-09 09:57:01', NULL);
INSERT INTO `cssd_monitoring_mesin_steam` VALUES ('53a62a27-cf50-4870-ba90-924c1393a1a0', '2023-08-09 08:00:00', 'pagi', '1', 'Getinge', NULL, NULL, '2023-08-09 09:54:47', '2023-08-09 09:54:47', NULL);
INSERT INTO `cssd_monitoring_mesin_steam` VALUES ('a389ebc1-872b-4519-be23-6268fbe103cd', '2023-08-14 08:00:00', 'pagi', '1', 'Getinge', NULL, NULL, '2023-08-14 09:56:56', '2023-08-14 09:56:56', NULL);
INSERT INTO `cssd_monitoring_mesin_steam` VALUES ('a7889a1a-4244-427b-95e8-523369ef8b60', '2023-08-15 07:04:00', 'pagi', '1', 'Getinge', NULL, NULL, '2023-08-15 10:05:30', '2023-08-15 10:05:30', NULL);

SET FOREIGN_KEY_CHECKS = 1;
