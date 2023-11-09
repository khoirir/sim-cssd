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

 Date: 09/10/2023 08:50:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_mesin_steam_detail
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_mesin_steam_detail`;
CREATE TABLE `cssd_monitoring_mesin_steam_detail`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_monitoring_mesin_steam` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_detail_penerimaan_alat_kotor` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT uuid,
  `id_alat` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_ruangan` char(36) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jumlah` int NOT NULL,
  `sisa_distribusi` int NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_detailmonitoringsteam_idmonitoringsteam_monitoringsteam_id`(`id_monitoring_mesin_steam`) USING BTREE,
  INDEX `fk_detailmonitoringsteam_idalatkotor_alatkotordetail_id`(`id_detail_penerimaan_alat_kotor`) USING BTREE,
  INDEX `fk_detailmonitoringsteam_idruangan_departemen_id`(`id_ruangan`) USING BTREE,
  INDEX `fk_detailmonitoringmesinsteam_idalat_setalat_id`(`id_alat`) USING BTREE,
  CONSTRAINT `fk_detailmonitoringmesinsteam_idalat_setalat_id` FOREIGN KEY (`id_alat`) REFERENCES `cssd_set_alat` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detailmonitoringsteam_idmonitoringsteam_monitoringsteam_id` FOREIGN KEY (`id_monitoring_mesin_steam`) REFERENCES `cssd_monitoring_mesin_steam` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detailmonitoringsteam_idruangan_departemen_id` FOREIGN KEY (`id_ruangan`) REFERENCES `departemen` (`dep_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_mesin_steam_detail
-- ----------------------------
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('13a313e0-3660-11ee-8c2a-14187762d6e2', '53a62a27-cf50-4870-ba90-924c1393a1a0', '', '001b74d5-1ae9-11ee-8009-14187762d6e2', 'CSSD', 15, 0, '2023-08-09 09:54:47', '2023-08-30 10:20:48', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('13d7a5c2-3660-11ee-8c2a-14187762d6e2', '53a62a27-cf50-4870-ba90-924c1393a1a0', '', '003bfb29-2b76-11ee-8009-14187762d6e2', 'CSSD', 20, 0, '2023-08-09 09:54:47', '2023-08-30 11:09:10', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('13da27c2-3660-11ee-8c2a-14187762d6e2', '53a62a27-cf50-4870-ba90-924c1393a1a0', '', '003260cc-2b76-11ee-8009-14187762d6e2', 'CSSD', 60, 0, '2023-08-09 09:54:47', '2023-09-04 09:43:58', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('13da7fb9-3660-11ee-8c2a-14187762d6e2', '53a62a27-cf50-4870-ba90-924c1393a1a0', '', '0168c955-0e73-11ee-8009-14187762d6e2', 'CSSD', 50, 0, '2023-08-09 09:54:47', '2023-09-04 09:42:30', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('39b53105-3a4e-11ee-8a45-14187762d6e2', 'a389ebc1-872b-4519-be23-6268fbe103cd', 'ff7b4c39-3a4d-11ee-8a45-14187762d6e2', '0f09b450-a335-4ffc-a2d5-fc1c9a1ef9fa', 'IGD', 10, 10, '2023-08-14 09:56:56', '2023-08-14 09:56:56', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('39c1192d-3a4e-11ee-8a45-14187762d6e2', 'a389ebc1-872b-4519-be23-6268fbe103cd', 'ff7eb048-3a4d-11ee-8a45-14187762d6e2', '33caa7e4-3d78-4357-9edb-b7763120834c', 'IGD', 5, 5, '2023-08-14 09:56:56', '2023-08-14 09:56:56', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('637c48aa-3660-11ee-8c2a-14187762d6e2', '41f87dd7-0017-4eb6-a01d-533ca2526ccf', 'f248a02e-34d4-11ee-8c2a-14187762d6e2', 'aa62919c-fb15-440b-abb7-edad3957006b', 'IKO', 10, 10, '2023-08-09 09:57:01', '2023-08-09 09:57:01', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('6381681d-3660-11ee-8c2a-14187762d6e2', '41f87dd7-0017-4eb6-a01d-533ca2526ccf', 'f248c7c3-34d4-11ee-8c2a-14187762d6e2', '09cab93f-d316-4119-9687-6d80bca5031d', 'IKO', 5, 5, '2023-08-09 09:57:01', '2023-08-09 09:57:01', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('6382d83b-3660-11ee-8c2a-14187762d6e2', '41f87dd7-0017-4eb6-a01d-533ca2526ccf', 'f248ce5a-34d4-11ee-8c2a-14187762d6e2', 'd8c3c582-d38b-4d97-843c-05f456c0afaa', 'IKO', 8, 8, '2023-08-09 09:57:01', '2023-08-09 09:57:01', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('836e872f-5838-11ee-81bd-14187762d6e2', '04f0fc9a-7739-456d-9098-0dec2e119a12', '', '05f43a03-21f1-11ee-8009-14187762d6e2', 'CSSD', 100, 55, '2023-09-21 11:37:10', '2023-10-03 14:57:42', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('83722806-5838-11ee-81bd-14187762d6e2', '04f0fc9a-7739-456d-9098-0dec2e119a12', '', '0263d338-10ba-11ee-8009-14187762d6e2', 'CSSD', 100, 100, '2023-09-21 11:37:10', '2023-09-21 11:37:10', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('83725df2-5838-11ee-81bd-14187762d6e2', '04f0fc9a-7739-456d-9098-0dec2e119a12', '', '01cef29f-30e0-11ee-8c2a-14187762d6e2', 'CSSD', 100, 100, '2023-09-21 11:37:10', '2023-09-21 11:37:10', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('8372b437-5838-11ee-81bd-14187762d6e2', '04f0fc9a-7739-456d-9098-0dec2e119a12', '', '01c909e5-30e0-11ee-8c2a-14187762d6e2', 'CSSD', 100, 100, '2023-09-21 11:37:10', '2023-09-21 11:37:10', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('8372f262-5838-11ee-81bd-14187762d6e2', '04f0fc9a-7739-456d-9098-0dec2e119a12', '', '08201118-1eee-11ee-8009-14187762d6e2', 'CSSD', 100, 80, '2023-09-21 11:37:10', '2023-10-03 14:57:42', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('96719724-3b18-11ee-8a45-14187762d6e2', 'a7889a1a-4244-427b-95e8-523369ef8b60', '', '003260cc-2b76-11ee-8009-14187762d6e2', 'CSSD', 20, 0, '2023-08-15 10:05:30', '2023-08-30 11:12:09', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_detail` VALUES ('967a2b29-3b18-11ee-8a45-14187762d6e2', 'a7889a1a-4244-427b-95e8-523369ef8b60', '', '001b74d5-1ae9-11ee-8009-14187762d6e2', 'CSSD', 15, 0, '2023-08-15 10:05:30', '2023-09-04 09:43:59', NULL);

SET FOREIGN_KEY_CHECKS = 1;
