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

 Date: 09/10/2023 08:50:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_mesin_steam_operator
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_mesin_steam_operator`;
CREATE TABLE `cssd_monitoring_mesin_steam_operator`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_monitoring_mesin_steam` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_operator` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_operatormonitoringsteam_idmonitoringsteam_monitoringsteam_id`(`id_monitoring_mesin_steam`) USING BTREE,
  INDEX `fk_operatormonitoringsteam_idpetugasoperator_pegawai_nik`(`id_operator`) USING BTREE,
  CONSTRAINT `fk_operatormonitoringsteam_idmonitoringsteam_monitoringsteam_id` FOREIGN KEY (`id_monitoring_mesin_steam`) REFERENCES `cssd_monitoring_mesin_steam` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_operatormonitoringsteam_idpetugasoperator_pegawai_nik` FOREIGN KEY (`id_operator`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_mesin_steam_operator
-- ----------------------------
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('139e2cad-3660-11ee-8c2a-14187762d6e2', '53a62a27-cf50-4870-ba90-924c1393a1a0', 'AINI', '2023-08-09 09:54:47', '2023-08-09 09:54:47', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('139e450e-3660-11ee-8c2a-14187762d6e2', '53a62a27-cf50-4870-ba90-924c1393a1a0', 'MEGA', '2023-08-09 09:54:47', '2023-08-09 09:54:47', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('39b188a3-3a4e-11ee-8a45-14187762d6e2', 'a389ebc1-872b-4519-be23-6268fbe103cd', 'MEGA', '2023-08-14 09:56:56', '2023-08-14 09:56:56', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('6374d2db-3660-11ee-8c2a-14187762d6e2', '41f87dd7-0017-4eb6-a01d-533ca2526ccf', 'AINI', '2023-08-09 09:57:01', '2023-08-09 09:57:01', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('6374e5ac-3660-11ee-8c2a-14187762d6e2', '41f87dd7-0017-4eb6-a01d-533ca2526ccf', 'MEGA', '2023-08-09 09:57:01', '2023-08-09 09:57:01', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('836136fa-5838-11ee-81bd-14187762d6e2', '04f0fc9a-7739-456d-9098-0dec2e119a12', 'AINI', '2023-09-21 11:37:10', '2023-09-21 11:37:10', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('836ddb21-5838-11ee-81bd-14187762d6e2', '04f0fc9a-7739-456d-9098-0dec2e119a12', 'MEGA', '2023-09-21 11:37:10', '2023-09-21 11:37:10', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_operator` VALUES ('9670837b-3b18-11ee-8a45-14187762d6e2', 'a7889a1a-4244-427b-95e8-523369ef8b60', 'DEBORA', '2023-08-15 10:05:30', '2023-08-15 10:05:30', NULL);

SET FOREIGN_KEY_CHECKS = 1;
