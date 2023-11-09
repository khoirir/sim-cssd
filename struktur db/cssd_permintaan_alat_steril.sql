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

 Date: 09/10/2023 08:51:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_permintaan_alat_steril
-- ----------------------------
DROP TABLE IF EXISTS `cssd_permintaan_alat_steril`;
CREATE TABLE `cssd_permintaan_alat_steril`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `tanggal_minta` datetime NOT NULL,
  `id_petugas_cssd` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_petugas_minta` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_ruangan` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_permintaanalatsteril_idpetugascssd_pegawai_nik`(`id_petugas_cssd`) USING BTREE,
  INDEX `fk_permintaanalatsteril_idpetugasminta_pegawai_nik`(`id_petugas_minta`) USING BTREE,
  INDEX `fk_permintaanalatsteril_idruangan_departemen_id`(`id_ruangan`) USING BTREE,
  CONSTRAINT `fk_permintaanalatsteril_idpetugascssd_pegawai_nik` FOREIGN KEY (`id_petugas_cssd`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_permintaanalatsteril_idpetugasminta_pegawai_nik` FOREIGN KEY (`id_petugas_minta`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_permintaanalatsteril_idruangan_departemen_id` FOREIGN KEY (`id_ruangan`) REFERENCES `departemen` (`dep_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_permintaan_alat_steril
-- ----------------------------
INSERT INTO `cssd_permintaan_alat_steril` VALUES ('092139ca-0ebc-454c-9167-7378a8f21bcd', '2023-08-30 11:00:00', 'DEBORA', 'ELSA', 'IGD', '2023-08-30 11:29:00', '2023-08-30 11:32:50', NULL);
INSERT INTO `cssd_permintaan_alat_steril` VALUES ('f3e15d9d-d353-43da-88bb-b5c99144e115', '2023-08-16 08:00:00', 'MEGA', 'ALI', 'IKO', '2023-08-16 10:41:52', '2023-08-16 10:41:52', NULL);

SET FOREIGN_KEY_CHECKS = 1;
