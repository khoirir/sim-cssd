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

 Date: 09/10/2023 08:52:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_uji_sealer_pouchs
-- ----------------------------
DROP TABLE IF EXISTS `cssd_uji_sealer_pouchs`;
CREATE TABLE `cssd_uji_sealer_pouchs`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `tanggal_uji` date NULL DEFAULT NULL,
  `id_petugas` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `suhu_mesin_200` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `speed_sedang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `hasil_uji` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `upload_bukti_uji` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_ujisealerpouchs_id_petugas_pegawai_nik`(`id_petugas`) USING BTREE,
  CONSTRAINT `fk_ujisealerpouchs_id_petugas_pegawai_nik` FOREIGN KEY (`id_petugas`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_uji_sealer_pouchs
-- ----------------------------
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('08f5a723-6845-4c8f-aeb7-3600859fec5d', '2023-06-15', 'YOLANDA', 'checked', 'checked', 'tidak', '1686816458_193a86da2e68917c5630.jpg', '', '2023-06-15 15:07:38', '2023-06-15 15:24:47', '2023-06-15 15:24:47');
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('26961edd-1235-4bf7-9809-828745592aad', '2023-06-16', 'YOLANDA', 'checked', 'checked', 'bocor', '1687161984_851e7d9438b7b35a5864.jpeg', '', '2023-06-19 15:06:24', '2023-06-19 15:07:01', NULL);
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('3c2ce22a-21a9-40be-a510-08643adacfd0', '2023-07-14', 'YOLANDA', 'checked', 'checked', 'bocor', '1689322609_1005190bf95d5e543100.jpg', '', '2023-07-14 15:16:49', '2023-07-14 15:16:49', NULL);
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('3d7655e5-fc26-4c55-a156-8c0205647abc', '2023-07-14', 'MEGA', 'checked', 'checked', 'tidak', '1689322595_f8423594bb5b08f113d0.jpeg', '', '2023-07-14 15:16:35', '2023-07-14 15:16:35', NULL);
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('4e40ef4d-f681-4722-b9c3-510fc50121f0', '2023-06-15', 'MEGA', 'checked', 'checked', 'tidak', '1686818551_344593e96150510b44fc.jpeg', 'tidak bocor', '2023-06-15 15:06:43', '2023-06-15 15:42:31', NULL);
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('5cc1acdc-a7a1-480e-b244-ff90ded823a6', '2023-06-15', 'AINI', 'checked', 'checked', 'tidak', '1686817340_5d1fd2fa62bd7b8d56b0.jpeg', 'tidak bocor', '2023-06-15 15:22:20', '2023-06-15 15:22:20', NULL);
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('8b568594-8561-41c9-b166-a2c93b44b453', '2023-06-15', 'DEBORA', 'checked', 'checked', 'bocor', '1686818603_1a4be60125bdf1853c8e.jpeg', 'bocor', '2023-06-15 15:43:23', '2023-06-19 14:44:09', '2023-06-19 14:44:09');
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('b9b9d7d1-c555-4091-bf1f-6c79e49919cc', '2023-07-14', 'DEBORA', 'checked', 'checked', 'tidak', '1689322311_7a78a3d118e5f422f8d4.jpeg', '', '2023-07-14 15:11:51', '2023-07-14 15:16:17', NULL);
INSERT INTO `cssd_uji_sealer_pouchs` VALUES ('c6e0de69-d725-44f2-acbf-e57319a3173b', '2023-06-19', 'DEBORA', 'checked', 'checked', 'tidak', '1687161890_351234bb6048630475ad.jpeg', 'tidak bocor', '2023-06-19 15:04:50', '2023-06-19 15:05:10', NULL);

SET FOREIGN_KEY_CHECKS = 1;
