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

 Date: 09/10/2023 08:51:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_penerimaan_alat_kotor_detail
-- ----------------------------
DROP TABLE IF EXISTS `cssd_penerimaan_alat_kotor_detail`;
CREATE TABLE `cssd_penerimaan_alat_kotor_detail`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_master` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_set_alat` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `jumlah` int NOT NULL,
  `enzym` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `dtt` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ultrasonic` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bilas` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `washer` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pemilihan_mesin` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_proses` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `sisa` int NULL DEFAULT 0,
  `status_distribusi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `sisa_distribusi` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_penerimaan_alat_kotor_detail_id_master_penerimaan_alat_id`(`id_master`) USING BTREE,
  INDEX `fk_penerimaan_alat_kotor_detail_id_set_alat_setalat_id`(`id_set_alat`) USING BTREE,
  CONSTRAINT `fk_penerimaan_alat_kotor_detail_id_master_penerimaan_alat_id` FOREIGN KEY (`id_master`) REFERENCES `cssd_penerimaan_alat_kotor` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_penerimaan_alat_kotor_detail_id_set_alat_setalat_id` FOREIGN KEY (`id_set_alat`) REFERENCES `cssd_set_alat` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_penerimaan_alat_kotor_detail
-- ----------------------------
INSERT INTO `cssd_penerimaan_alat_kotor_detail` VALUES ('f248a02e-34d4-11ee-8c2a-14187762d6e2', '19997b16-d79f-4338-b8cc-6c7b3233703d', 'aa62919c-fb15-440b-abb7-edad3957006b', 10, 'enzym', 'dtt', 'ultrasonic', 'bilas', 'washer', 'Steam', 'Diproses', 0, 'Distribusi', 0, '2023-08-07 10:46:19', '2023-08-16 10:41:53', NULL);
INSERT INTO `cssd_penerimaan_alat_kotor_detail` VALUES ('f248c7c3-34d4-11ee-8c2a-14187762d6e2', '19997b16-d79f-4338-b8cc-6c7b3233703d', '09cab93f-d316-4119-9687-6d80bca5031d', 5, 'enzym', 'dtt', 'ultrasonic', 'bilas', 'washer', 'Steam', 'Diproses', 0, 'Distribusi', 0, '2023-08-07 10:46:19', '2023-08-16 10:41:53', NULL);
INSERT INTO `cssd_penerimaan_alat_kotor_detail` VALUES ('f248ce5a-34d4-11ee-8c2a-14187762d6e2', '19997b16-d79f-4338-b8cc-6c7b3233703d', 'd8c3c582-d38b-4d97-843c-05f456c0afaa', 8, 'enzym', 'dtt', 'ultrasonic', 'bilas', 'washer', 'Steam', 'Diproses', 0, 'Distribusi', 0, '2023-08-07 10:46:19', '2023-08-16 10:41:53', NULL);
INSERT INTO `cssd_penerimaan_alat_kotor_detail` VALUES ('ff7b4c39-3a4d-11ee-8a45-14187762d6e2', '00439f10-fed1-4480-bdf8-08f6214eef09', '0f09b450-a335-4ffc-a2d5-fc1c9a1ef9fa', 10, 'enzym', 'dtt', 'ultrasonic', 'bilas', 'washer', 'Steam', 'Diproses', 0, 'Distribusi', 0, '2023-08-14 09:55:19', '2023-08-30 11:32:51', NULL);
INSERT INTO `cssd_penerimaan_alat_kotor_detail` VALUES ('ff7eb048-3a4d-11ee-8a45-14187762d6e2', '00439f10-fed1-4480-bdf8-08f6214eef09', '33caa7e4-3d78-4357-9edb-b7763120834c', 5, 'enzym', 'dtt', 'ultrasonic', 'bilas', 'washer', 'Steam', 'Diproses', 0, 'Distribusi', 0, '2023-08-14 09:55:19', '2023-08-30 11:32:23', NULL);

SET FOREIGN_KEY_CHECKS = 1;
