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

 Date: 09/10/2023 08:51:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_permintaan_bmhp_steril
-- ----------------------------
DROP TABLE IF EXISTS `cssd_permintaan_bmhp_steril`;
CREATE TABLE `cssd_permintaan_bmhp_steril`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `tanggal_minta` datetime NOT NULL,
  `id_petugas_cssd` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_petugas_minta` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_ruangan` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_permintaanbmhp_idpetugascssd_pegawai_nik`(`id_petugas_cssd`) USING BTREE,
  INDEX `fk_permintaanbmhp_idpetugasminta_pegawai_nik`(`id_petugas_minta`) USING BTREE,
  INDEX `fk_permintaanbmhp_idruangan_departemen_id`(`id_ruangan`) USING BTREE,
  CONSTRAINT `fk_permintaanbmhp_idpetugascssd_pegawai_nik` FOREIGN KEY (`id_petugas_cssd`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_permintaanbmhp_idpetugasminta_pegawai_nik` FOREIGN KEY (`id_petugas_minta`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_permintaanbmhp_idruangan_departemen_id` FOREIGN KEY (`id_ruangan`) REFERENCES `departemen` (`dep_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_permintaan_bmhp_steril
-- ----------------------------
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('04d0dc94-df78-4a3e-9c5b-91e3593f0ce6', '2023-09-04 09:10:00', 'MEGA', 'ALI', 'IKO', '2023-09-04 09:43:58', '2023-09-04 09:43:58', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('2d58ae42-4736-46cd-ac7e-403fc28c05d4', '2023-09-21 11:35:00', 'MEGA', 'ALI', 'IKO', '2023-09-21 11:39:15', '2023-09-21 11:39:15', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('35a57606-ffce-4c00-bbdc-dfaaf0fee5d3', '2023-08-16 10:00:00', 'AINI', 'ALI', 'IKO', '2023-08-16 10:38:26', '2023-08-30 11:23:54', '2023-08-30 11:23:54');
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('427d129b-7e6c-405d-9c5c-c0fff4c8873f', '2023-09-04 09:00:00', 'DEBORA', 'VINDA', 'PERI', '2023-09-04 09:42:30', '2023-09-04 09:42:30', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('452260fe-0a70-43a0-ad4a-47a7202604b0', '2023-08-21 09:00:00', 'DEBORA', 'ELSA', 'IGD', '2023-08-21 09:16:15', '2023-08-30 11:12:09', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('6b104249-7391-458c-a061-60653530c64b', '2023-09-21 11:39:00', 'MEGA', 'ALI', 'IKO', '2023-09-21 11:39:36', '2023-09-21 11:39:36', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('bbfa8019-7e75-4e6c-b4e9-43bddb800158', '2023-10-03 14:00:00', 'MEGA', 'SRIYUL', 'SER', '2023-10-03 14:57:42', '2023-10-03 14:57:42', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril` VALUES ('c9d2e1cd-6a42-4684-8bba-fc9ba0da9154', '2023-09-22 10:00:00', 'DEBORA', 'VINDA', 'PERI', '2023-09-22 10:06:57', '2023-09-25 12:04:16', '2023-09-25 12:04:16');

SET FOREIGN_KEY_CHECKS = 1;
