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

 Date: 09/10/2023 08:51:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_permintaan_bmhp_steril_detail
-- ----------------------------
DROP TABLE IF EXISTS `cssd_permintaan_bmhp_steril_detail`;
CREATE TABLE `cssd_permintaan_bmhp_steril_detail`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_master` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_bmhp` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_monitoring_mesin_steam_detail` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT uuid,
  `jumlah` int NOT NULL,
  `harga` decimal(11, 2) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_detailpermintaanbmhp_idmaster_master_id`(`id_master`) USING BTREE,
  INDEX `fk_detailpermintaanbmhp_idalat_setalat_id`(`id_bmhp`) USING BTREE,
  INDEX `fk_detailpermintaanbmhp_iddetailmesinsteam_mesinsteamdetail_id`(`id_monitoring_mesin_steam_detail`) USING BTREE,
  CONSTRAINT `fk_detailpermintaanbmhp_idalat_setalat_id` FOREIGN KEY (`id_bmhp`) REFERENCES `cssd_set_alat` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detailpermintaanbmhp_iddetailmesinsteam_mesinsteamdetail_id` FOREIGN KEY (`id_monitoring_mesin_steam_detail`) REFERENCES `cssd_monitoring_mesin_steam_detail` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detailpermintaanbmhp_idmaster_master_id` FOREIGN KEY (`id_master`) REFERENCES `cssd_permintaan_bmhp_steril` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_permintaan_bmhp_steril_detail
-- ----------------------------
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('12c91d8f-58f5-11ee-81bd-14187762d6e2', 'c9d2e1cd-6a42-4684-8bba-fc9ba0da9154', '05f43a03-21f1-11ee-8009-14187762d6e2', '836e872f-5838-11ee-81bd-14187762d6e2', 25, 5000.00, '', '2023-09-22 10:06:57', '2023-09-25 12:04:16', '2023-09-25 12:04:16');
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('36240adf-46eb-11ee-bebb-14187762d6e2', '35a57606-ffce-4c00-bbdc-dfaaf0fee5d3', '0168c955-0e73-11ee-8009-14187762d6e2', '13da7fb9-3660-11ee-8c2a-14187762d6e2', 25, 9500.00, '', '2023-08-30 11:11:04', '2023-08-30 11:23:54', '2023-08-30 11:23:54');
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('58b6b4ad-3be6-11ee-8a45-14187762d6e2', '35a57606-ffce-4c00-bbdc-dfaaf0fee5d3', '001b74d5-1ae9-11ee-8009-14187762d6e2', '967a2b29-3b18-11ee-8a45-14187762d6e2', 15, 6000.00, '', '2023-08-16 10:38:26', '2023-08-30 11:23:54', '2023-08-30 11:23:54');
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('58b84a95-3be6-11ee-8a45-14187762d6e2', '35a57606-ffce-4c00-bbdc-dfaaf0fee5d3', '003260cc-2b76-11ee-8009-14187762d6e2', '96719724-3b18-11ee-8a45-14187762d6e2', 20, 4500.00, '', '2023-08-16 10:38:26', '2023-08-30 11:10:52', '2023-08-30 11:10:52');
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('5c7c8034-46eb-11ee-bebb-14187762d6e2', '452260fe-0a70-43a0-ad4a-47a7202604b0', '003260cc-2b76-11ee-8009-14187762d6e2', '96719724-3b18-11ee-8a45-14187762d6e2', 20, 4500.00, '', '2023-08-30 11:12:09', '2023-08-30 11:12:09', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('7d016884-61c2-11ee-81bd-14187762d6e2', 'bbfa8019-7e75-4e6c-b4e9-43bddb800158', '05f43a03-21f1-11ee-8009-14187762d6e2', '836e872f-5838-11ee-81bd-14187762d6e2', 10, 5000.00, '', '2023-10-03 14:57:42', '2023-10-03 14:57:42', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('7d026d38-61c2-11ee-81bd-14187762d6e2', 'bbfa8019-7e75-4e6c-b4e9-43bddb800158', '08201118-1eee-11ee-8009-14187762d6e2', '8372f262-5838-11ee-81bd-14187762d6e2', 20, 1000.00, '', '2023-10-03 14:57:42', '2023-10-03 14:57:42', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('af589cf9-3fc8-11ee-8a45-14187762d6e2', '452260fe-0a70-43a0-ad4a-47a7202604b0', '001b74d5-1ae9-11ee-8009-14187762d6e2', '13a313e0-3660-11ee-8c2a-14187762d6e2', 15, 6000.00, '', '2023-08-21 09:16:15', '2023-08-30 10:20:48', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('af5d63f7-3fc8-11ee-8a45-14187762d6e2', '452260fe-0a70-43a0-ad4a-47a7202604b0', '003260cc-2b76-11ee-8009-14187762d6e2', '13da27c2-3660-11ee-8c2a-14187762d6e2', 60, 4500.00, '', '2023-08-21 09:16:15', '2023-08-30 10:57:49', '2023-08-30 10:57:49');
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('afb0f548-4acc-11ee-bf22-14187762d6e2', '427d129b-7e6c-405d-9c5c-c0fff4c8873f', '0168c955-0e73-11ee-8009-14187762d6e2', '13da7fb9-3660-11ee-8c2a-14187762d6e2', 50, 9500.00, '', '2023-09-04 09:42:30', '2023-09-04 09:42:30', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('afb3d587-4acc-11ee-bf22-14187762d6e2', '427d129b-7e6c-405d-9c5c-c0fff4c8873f', '003260cc-2b76-11ee-8009-14187762d6e2', '13da27c2-3660-11ee-8c2a-14187762d6e2', 30, 4500.00, '', '2023-09-04 09:42:30', '2023-09-04 09:42:30', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('cd9c8337-5838-11ee-81bd-14187762d6e2', '2d58ae42-4736-46cd-ac7e-403fc28c05d4', '05f43a03-21f1-11ee-8009-14187762d6e2', '836e872f-5838-11ee-81bd-14187762d6e2', 20, 5000.00, 'sebagian', '2023-09-21 11:39:15', '2023-09-21 11:39:15', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('cf0e52c2-46ea-11ee-bebb-14187762d6e2', '452260fe-0a70-43a0-ad4a-47a7202604b0', '003260cc-2b76-11ee-8009-14187762d6e2', '13da27c2-3660-11ee-8c2a-14187762d6e2', 60, 4500.00, '', '2023-08-30 11:08:11', '2023-08-30 11:09:22', '2023-08-30 11:09:22');
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('da177af0-5838-11ee-81bd-14187762d6e2', '6b104249-7391-458c-a061-60653530c64b', '05f43a03-21f1-11ee-8009-14187762d6e2', '836e872f-5838-11ee-81bd-14187762d6e2', 15, 5000.00, '', '2023-09-21 11:39:36', '2023-09-21 11:39:36', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('e43f6ce6-4acc-11ee-bf22-14187762d6e2', '04d0dc94-df78-4a3e-9c5b-91e3593f0ce6', '003260cc-2b76-11ee-8009-14187762d6e2', '13da27c2-3660-11ee-8c2a-14187762d6e2', 30, 4500.00, '', '2023-09-04 09:43:58', '2023-09-04 09:43:58', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('e44056dc-4acc-11ee-bf22-14187762d6e2', '04d0dc94-df78-4a3e-9c5b-91e3593f0ce6', '001b74d5-1ae9-11ee-8009-14187762d6e2', '967a2b29-3b18-11ee-8a45-14187762d6e2', 15, 6000.00, '', '2023-09-04 09:43:59', '2023-09-04 09:43:59', NULL);
INSERT INTO `cssd_permintaan_bmhp_steril_detail` VALUES ('f22d4d57-46ea-11ee-bebb-14187762d6e2', '452260fe-0a70-43a0-ad4a-47a7202604b0', '003bfb29-2b76-11ee-8009-14187762d6e2', '13d7a5c2-3660-11ee-8c2a-14187762d6e2', 20, 3800.00, '', '2023-08-30 11:09:10', '2023-08-30 11:09:10', NULL);

SET FOREIGN_KEY_CHECKS = 1;
