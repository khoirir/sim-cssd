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

 Date: 09/10/2023 08:48:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_jenis_set_alat
-- ----------------------------
DROP TABLE IF EXISTS `cssd_jenis_set_alat`;
CREATE TABLE `cssd_jenis_set_alat`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `nama_jenis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_jenis_set_alat
-- ----------------------------
INSERT INTO `cssd_jenis_set_alat` VALUES ('0e0dae9d-34ce-11ee-8c2a-14187762d6e2', 'BMHP', '2023-08-09 09:25:48', '2023-08-09 09:25:48', NULL);
INSERT INTO `cssd_jenis_set_alat` VALUES ('1e0dae0d-34ce-11ee-8c2a-14167563d6e2', 'Poch', '2023-08-07 10:28:36', '2023-08-07 10:28:39', NULL);
INSERT INTO `cssd_jenis_set_alat` VALUES ('797f2a7a-38a3-4a13-a9f8-ba89ec0188cb', 'jenis baru i', '2023-08-08 13:57:29', '2023-08-08 14:00:05', '2023-08-08 14:00:05');
INSERT INTO `cssd_jenis_set_alat` VALUES ('8e0dae6d-37ce-13ee-8c2a-14167589d6e2', 'Linen', '2023-08-07 10:28:40', '2023-08-07 10:28:40', NULL);
INSERT INTO `cssd_jenis_set_alat` VALUES ('9e0dae9d-34ce-11ee-8c2a-14187762d6e2', 'Set', '2023-08-07 10:04:17', '2023-08-07 10:04:20', NULL);

SET FOREIGN_KEY_CHECKS = 1;
