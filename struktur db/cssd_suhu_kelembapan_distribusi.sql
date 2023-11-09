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

 Date: 09/10/2023 08:52:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_suhu_kelembapan_distribusi
-- ----------------------------
DROP TABLE IF EXISTS `cssd_suhu_kelembapan_distribusi`;
CREATE TABLE `cssd_suhu_kelembapan_distribusi`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `tgl_catat` date NULL DEFAULT NULL,
  `id_petugas` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `suhu` int NULL DEFAULT NULL,
  `kelembapan` int NULL DEFAULT NULL,
  `tindakan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `hasil_tindakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_suhu_kelembapan_distribusi_id_petugas_pegawai_nik`(`id_petugas`) USING BTREE,
  CONSTRAINT `fk_suhu_kelembapan_distribusi_id_petugas_pegawai_nik` FOREIGN KEY (`id_petugas`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_suhu_kelembapan_distribusi
-- ----------------------------
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('11a04bd4-ab3d-4e86-bd49-d4aef700f01f', '2023-07-17', 'DEBORA', 12, 40, '', NULL, '2023-07-18 09:49:39', '2023-08-03 15:13:11', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('1242449b-a693-41d5-9c7c-e31cfaeab728', '2023-06-19', 'DEBORA', 25, 65, 'disabled', NULL, '2023-06-19 15:30:51', '2023-06-19 15:30:51', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('13c237e1-aa33-4d51-903f-1e42888cb6d0', '2023-06-19', 'DEBORA', 30, 65, 'disabled', NULL, '2023-06-19 15:07:54', '2023-06-19 15:25:24', '2023-06-19 15:25:24');
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('212b4437-9d4d-4a15-9017-d5728ff33ace', '2023-07-18', 'MEGA', 30, 55, 'disabled', NULL, '2023-07-18 09:49:26', '2023-07-18 09:50:34', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('34accdda-979b-4506-ad85-6c4766db90b3', '2023-06-16', 'MEGA', 23, 55, 'disabled', NULL, '2023-06-16 15:52:08', '2023-06-16 15:52:31', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('4ef189ac-4677-4afc-91d5-681f37fac1ce', '2023-06-10', 'MEGA', 30, 65, 'disabled', NULL, '2023-06-13 14:22:31', '2023-06-13 14:23:05', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('603e0857-0516-428d-b2c7-6664c9dd8dec', '2023-06-14', 'DEBORA', 22, 66, 'disabled', NULL, '2023-06-14 10:15:01', '2023-06-14 10:15:21', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('72caa23b-5961-4f79-82b7-39a799e4b2a6', '2023-06-15', 'DEBORA', 29, 65, 'disabled', NULL, '2023-06-16 15:52:49', '2023-06-16 15:53:00', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('ae2769b9-35d7-4651-910a-1e2dc59ee384', '2023-06-13', 'DEBORA', 25, 76, '', 'sudah selesai', '2023-06-13 09:41:06', '2023-06-16 15:55:48', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('b5fbaa34-f397-4963-a04c-23c34fb4f8e5', '2023-06-11', 'AINI', 30, 40, 'disabled', NULL, '2023-06-13 09:44:47', '2023-06-13 09:46:35', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('b71f52c6-7c70-463a-b752-d52cdfcec239', '2023-08-04', 'MEGA', 24, 80, '', NULL, '2023-08-04 12:54:51', '2023-08-04 12:54:51', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('e06483ad-4874-4491-9f62-cbf50159dee7', '2023-06-17', 'MEGA', 25, 50, 'disabled', NULL, '2023-06-19 15:31:13', '2023-06-19 15:31:13', NULL);
INSERT INTO `cssd_suhu_kelembapan_distribusi` VALUES ('fd9d2fa5-a6a7-41be-ba52-e1d663c11d71', '2023-06-12', 'MEGA', 21, 40, '', 'suhu kurang dari batas', '2023-06-13 09:44:03', '2023-06-16 15:56:21', NULL);

SET FOREIGN_KEY_CHECKS = 1;
