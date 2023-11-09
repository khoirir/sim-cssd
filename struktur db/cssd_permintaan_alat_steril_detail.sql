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

 Date: 09/10/2023 08:51:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_permintaan_alat_steril_detail
-- ----------------------------
DROP TABLE IF EXISTS `cssd_permintaan_alat_steril_detail`;
CREATE TABLE `cssd_permintaan_alat_steril_detail`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_master` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_detail_penerimaan_alat_kotor` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_alat` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `jumlah` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_detailpermintaanalatsteril_master_id`(`id_master`) USING BTREE,
  INDEX `fk_detailpermintaanslatsteril_setalat_id`(`id_alat`) USING BTREE,
  CONSTRAINT `fk_detailpermintaanalatsteril_master_id` FOREIGN KEY (`id_master`) REFERENCES `cssd_permintaan_alat_steril` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detailpermintaanslatsteril_setalat_id` FOREIGN KEY (`id_alat`) REFERENCES `cssd_set_alat` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_permintaan_alat_steril_detail
-- ----------------------------
INSERT INTO `cssd_permintaan_alat_steril_detail` VALUES ('3058367f-46ee-11ee-bebb-14187762d6e2', '092139ca-0ebc-454c-9167-7378a8f21bcd', 'ff7eb048-3a4d-11ee-8a45-14187762d6e2', '33caa7e4-3d78-4357-9edb-b7763120834c', 5, 'lengkap', '2023-08-30 11:32:23', '2023-08-30 11:32:23', NULL);
INSERT INTO `cssd_permintaan_alat_steril_detail` VALUES ('40c7c414-46ee-11ee-bebb-14187762d6e2', '092139ca-0ebc-454c-9167-7378a8f21bcd', 'ff7b4c39-3a4d-11ee-8a45-14187762d6e2', '0f09b450-a335-4ffc-a2d5-fc1c9a1ef9fa', 10, 'diambil semua', '2023-08-30 11:32:51', '2023-08-30 11:32:51', NULL);
INSERT INTO `cssd_permintaan_alat_steril_detail` VALUES ('b74265c5-46ed-11ee-bebb-14187762d6e2', '092139ca-0ebc-454c-9167-7378a8f21bcd', 'ff7b4c39-3a4d-11ee-8a45-14187762d6e2', '0f09b450-a335-4ffc-a2d5-fc1c9a1ef9fa', 10, '', '2023-08-30 11:29:00', '2023-08-30 11:32:34', '2023-08-30 11:32:34');
INSERT INTO `cssd_permintaan_alat_steril_detail` VALUES ('b743ce31-46ed-11ee-bebb-14187762d6e2', '092139ca-0ebc-454c-9167-7378a8f21bcd', 'ff7eb048-3a4d-11ee-8a45-14187762d6e2', '33caa7e4-3d78-4357-9edb-b7763120834c', 5, '', '2023-08-30 11:29:00', '2023-08-30 11:29:25', '2023-08-30 11:29:25');
INSERT INTO `cssd_permintaan_alat_steril_detail` VALUES ('d407df42-3be6-11ee-8a45-14187762d6e2', 'f3e15d9d-d353-43da-88bb-b5c99144e115', 'f248a02e-34d4-11ee-8c2a-14187762d6e2', 'aa62919c-fb15-440b-abb7-edad3957006b', 10, '', '2023-08-16 10:41:53', '2023-08-16 10:41:53', NULL);
INSERT INTO `cssd_permintaan_alat_steril_detail` VALUES ('d409cdcb-3be6-11ee-8a45-14187762d6e2', 'f3e15d9d-d353-43da-88bb-b5c99144e115', 'f248c7c3-34d4-11ee-8c2a-14187762d6e2', '09cab93f-d316-4119-9687-6d80bca5031d', 5, '', '2023-08-16 10:41:53', '2023-08-16 10:41:53', NULL);
INSERT INTO `cssd_permintaan_alat_steril_detail` VALUES ('d40bbdd0-3be6-11ee-8a45-14187762d6e2', 'f3e15d9d-d353-43da-88bb-b5c99144e115', 'f248ce5a-34d4-11ee-8c2a-14187762d6e2', 'd8c3c582-d38b-4d97-843c-05f456c0afaa', 8, '', '2023-08-16 10:41:53', '2023-08-16 10:41:53', NULL);

SET FOREIGN_KEY_CHECKS = 1;
