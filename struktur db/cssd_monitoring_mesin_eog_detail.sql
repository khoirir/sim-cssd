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

 Date: 09/10/2023 08:49:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_mesin_eog_detail
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_mesin_eog_detail`;
CREATE TABLE `cssd_monitoring_mesin_eog_detail`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_monitoring_mesin_eog` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_detail_penerimaan_alat_kotor` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT uuid,
  `id_alat` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_ruangan` char(36) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_detailmesineog_idmaster_mesineog_id`(`id_monitoring_mesin_eog`) USING BTREE,
  INDEX `fk_detailmesineog_idalat_setalat_id`(`id_alat`) USING BTREE,
  INDEX `fk_detailmesineog_idruangan_departemen_id`(`id_ruangan`) USING BTREE,
  CONSTRAINT `fk_detailmesineog_idalat_setalat_id` FOREIGN KEY (`id_alat`) REFERENCES `cssd_set_alat` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detailmesineog_idmaster_mesineog_id` FOREIGN KEY (`id_monitoring_mesin_eog`) REFERENCES `cssd_monitoring_mesin_eog` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detailmesineog_idruangan_departemen_id` FOREIGN KEY (`id_ruangan`) REFERENCES `departemen` (`dep_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_mesin_eog_detail
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
