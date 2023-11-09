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

 Date: 09/10/2023 08:51:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_penerimaan_alat_kotor
-- ----------------------------
DROP TABLE IF EXISTS `cssd_penerimaan_alat_kotor`;
CREATE TABLE `cssd_penerimaan_alat_kotor`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `tanggal_penerimaan` datetime NOT NULL,
  `id_petugas_cssd` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_petugas_penyetor` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_ruangan` char(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `upload_dokumentasi` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_penerimaan_alat_kotor_id_petugas_cssd_pegawai_nik`(`id_petugas_cssd`) USING BTREE,
  INDEX `fk_penerimaan_alat_kotor_id_petugas_penyetor_pegawai_nik`(`id_petugas_penyetor`) USING BTREE,
  INDEX `fk_penerimaan_alat_kotor_id_ruangan_departemen_dep_id`(`id_ruangan`) USING BTREE,
  CONSTRAINT `fk_penerimaan_alat_kotor_id_petugas_cssd_pegawai_nik` FOREIGN KEY (`id_petugas_cssd`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_penerimaan_alat_kotor_id_petugas_penyetor_pegawai_nik` FOREIGN KEY (`id_petugas_penyetor`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_penerimaan_alat_kotor_id_ruangan_departemen_dep_id` FOREIGN KEY (`id_ruangan`) REFERENCES `departemen` (`dep_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_penerimaan_alat_kotor
-- ----------------------------
INSERT INTO `cssd_penerimaan_alat_kotor` VALUES ('00439f10-fed1-4480-bdf8-08f6214eef09', '2023-08-14 07:00:00', 'DEBORA', 'ELSA', 'IGD', '1691981741_be50d7c5246348fb2d35.jpg', '2023-08-14 09:55:18', '2023-08-14 09:55:56', NULL);
INSERT INTO `cssd_penerimaan_alat_kotor` VALUES ('19997b16-d79f-4338-b8cc-6c7b3233703d', '2023-08-07 10:00:00', 'MEGA', 'ALI', 'IKO', '1691380011_316996e722a5847ba66a.jpg', '2023-08-07 10:46:19', '2023-08-07 10:46:51', NULL);

SET FOREIGN_KEY_CHECKS = 1;
