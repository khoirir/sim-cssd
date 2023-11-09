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

 Date: 09/10/2023 08:50:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_mesin_plasma_verifikasi
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_mesin_plasma_verifikasi`;
CREATE TABLE `cssd_monitoring_mesin_plasma_verifikasi`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_monitoring_mesin_plasma` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `waktu_keluar_alat` datetime NOT NULL,
  `data_print` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `indikator_eksternal` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `indikator_internal` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `indikator_biologi` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_petugas_verifikator` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hasil_verifikasi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_verifikasimesinplasma_idmaster_mesinplasma_id`(`id_monitoring_mesin_plasma`) USING BTREE,
  INDEX `fk_verifikasimesinplasma_idverifikator_pegawai_nik`(`id_petugas_verifikator`) USING BTREE,
  CONSTRAINT `fk_verifikasimesinplasma_idmaster_mesinplasma_id` FOREIGN KEY (`id_monitoring_mesin_plasma`) REFERENCES `cssd_monitoring_mesin_plasma` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_verifikasimesinplasma_idverifikator_pegawai_nik` FOREIGN KEY (`id_petugas_verifikator`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_mesin_plasma_verifikasi
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
