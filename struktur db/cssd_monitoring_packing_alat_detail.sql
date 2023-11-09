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

 Date: 09/10/2023 08:51:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_packing_alat_detail
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_packing_alat_detail`;
CREATE TABLE `cssd_monitoring_packing_alat_detail`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `id_master` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `id_alat` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `bersih` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tajam` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `layak` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `indikator` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_petugas` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_packingalatmaster_packingalatdetail`(`id_master`) USING BTREE,
  INDEX `fk_packingalat_idpetugas_pegawai_nik`(`id_petugas`) USING BTREE,
  INDEX `fk_packingalat_idalat_setalat_id`(`id_alat`) USING BTREE,
  CONSTRAINT `fk_packingalat_idalat_setalat_id` FOREIGN KEY (`id_alat`) REFERENCES `cssd_set_alat` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_packingalat_idpetugas_pegawai_nik` FOREIGN KEY (`id_petugas`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_packingalatmaster_packingalatdetail` FOREIGN KEY (`id_master`) REFERENCES `cssd_monitoring_packing_alat` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_packing_alat_detail
-- ----------------------------
INSERT INTO `cssd_monitoring_packing_alat_detail` VALUES ('1df522fa-34d5-11ee-8c2a-14187762d6e2', 'b72651cd-5bc7-4d14-af94-22105f9f9219', 'aa62919c-fb15-440b-abb7-edad3957006b', 'bersih', 'tajam', 'layak', 'indikator', 'DEBORA', '2023-08-07 10:47:32', '2023-08-07 10:47:32', NULL);
INSERT INTO `cssd_monitoring_packing_alat_detail` VALUES ('1df55a8a-34d5-11ee-8c2a-14187762d6e2', 'b72651cd-5bc7-4d14-af94-22105f9f9219', '09cab93f-d316-4119-9687-6d80bca5031d', 'bersih', 'tajam', 'layak', 'indikator', 'DEBORA', '2023-08-07 10:47:32', '2023-08-07 10:47:32', NULL);
INSERT INTO `cssd_monitoring_packing_alat_detail` VALUES ('1df56280-34d5-11ee-8c2a-14187762d6e2', 'b72651cd-5bc7-4d14-af94-22105f9f9219', 'd8c3c582-d38b-4d97-843c-05f456c0afaa', 'bersih', 'tajam', 'layak', 'indikator', 'DEBORA', '2023-08-07 10:47:32', '2023-08-07 10:47:32', NULL);

SET FOREIGN_KEY_CHECKS = 1;
