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

 Date: 09/10/2023 08:49:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_kepatuhan_apd_detail
-- ----------------------------
DROP TABLE IF EXISTS `cssd_kepatuhan_apd_detail`;
CREATE TABLE `cssd_kepatuhan_apd_detail`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `id_master` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `id_petugas` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `handschoen` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `masker` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `apron` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `goggle` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sepatu_boot` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `penutup_kepala` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_apdmaster_apddetail`(`id_master`) USING BTREE,
  INDEX `fk_pegawainik_detailpetugas`(`id_petugas`) USING BTREE,
  CONSTRAINT `fk_apdmaster_apddetail` FOREIGN KEY (`id_master`) REFERENCES `cssd_kepatuhan_apd` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pegawainik_detailpetugas` FOREIGN KEY (`id_petugas`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_kepatuhan_apd_detail
-- ----------------------------
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('046044c0-194d-11ee-8009-14187762d6e2', '7632d8fe-b9b7-4985-adfc-50a92df15313', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 09:55:28', '2023-07-03 09:55:28', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('06b4f689-04d4-11ee-a24c-14187762d6e2', '661eb2f0-1922-41b7-a28f-947b9b09457a', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-07 08:41:06', '2023-06-12 10:59:20', '2023-06-12 10:59:20');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('086a85ad-194b-11ee-8009-14187762d6e2', '2fe1e134-ed6a-4577-b547-4e1806451649', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 09:41:16', '2023-07-03 10:01:27', '2023-07-03 10:01:27');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('086b4027-194b-11ee-8009-14187762d6e2', '2fe1e134-ed6a-4577-b547-4e1806451649', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 09:41:16', '2023-07-03 09:41:16', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('0897c715-0e6a-11ee-8009-14187762d6e2', 'e3c15ae0-3a22-4ae5-943b-71d49c99b86f', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:25:15', '2023-06-19 13:25:15', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('09ec9285-0347-11ee-a24c-14187762d6e2', '1f72af6a-5e1b-42f5-812c-8c4c33d160ab', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:19:19', '2023-06-06 14:58:20', '2023-06-06 14:58:20');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('09f02868-0347-11ee-a24c-14187762d6e2', '1f72af6a-5e1b-42f5-812c-8c4c33d160ab', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:19:19', '2023-06-06 14:56:40', '2023-06-06 14:56:40');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('361afbd5-0e6c-11ee-8009-14187762d6e2', '133997d9-0781-4150-9dd3-30245fb497ff', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:40:51', '2023-06-19 13:41:08', '2023-06-19 13:41:08');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('38b88140-0349-11ee-a24c-14187762d6e2', 'e40d2a87-0c03-4ff1-ba74-6a7ef177fc89', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:34:56', '2023-06-05 09:34:56', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('38b8d58e-0349-11ee-a24c-14187762d6e2', 'e40d2a87-0c03-4ff1-ba74-6a7ef177fc89', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:34:56', '2023-06-05 09:34:56', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('40c8d7c8-0e6c-11ee-8009-14187762d6e2', '133997d9-0781-4150-9dd3-30245fb497ff', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:41:08', '2023-06-19 13:41:08', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('40c8e3cd-0e6c-11ee-8009-14187762d6e2', '133997d9-0781-4150-9dd3-30245fb497ff', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:41:08', '2023-06-19 13:41:08', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('43bf2a6d-0349-11ee-a24c-14187762d6e2', '661eb2f0-1922-41b7-a28f-947b9b09457a', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:35:15', '2023-06-12 10:59:20', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('43c24113-0349-11ee-a24c-14187762d6e2', '661eb2f0-1922-41b7-a28f-947b9b09457a', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:35:15', '2023-06-07 11:07:00', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('44ff1b4f-0414-11ee-a24c-14187762d6e2', '84a7e1c4-3252-4c17-83d0-a1f9ec5bd2a7', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', '', '', '', '2023-06-06 09:48:26', '2023-06-06 15:12:25', '2023-06-06 15:12:25');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4503a0ae-0414-11ee-a24c-14187762d6e2', '84a7e1c4-3252-4c17-83d0-a1f9ec5bd2a7', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-06 09:48:26', '2023-06-06 09:48:26', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4686ad69-08eb-11ee-a24c-14187762d6e2', '5c067214-84d0-4224-ba3c-2b6046073af0', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-12 13:37:40', '2023-06-12 13:37:40', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4686c613-08eb-11ee-a24c-14187762d6e2', '5c067214-84d0-4224-ba3c-2b6046073af0', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-12 13:37:40', '2023-06-12 13:38:12', '2023-06-12 13:38:12');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4686c83f-08eb-11ee-a24c-14187762d6e2', '5c067214-84d0-4224-ba3c-2b6046073af0', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-12 13:37:40', '2023-06-12 13:38:12', '2023-06-12 13:38:12');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4991803b-08d7-11ee-a24c-14187762d6e2', '2ec43326-599b-49db-88f2-14ee06fdc93e', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-12 11:14:35', '2023-06-12 11:14:35', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('49ae206a-0349-11ee-a24c-14187762d6e2', '608c0844-b785-42e5-b212-a196f1a5b803', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:35:25', '2023-06-05 09:35:25', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4c46fec1-05ad-11ee-a24c-14187762d6e2', 'f6067deb-0674-429f-b602-2db80e4bdc2e', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'apd lengkap', '2023-06-08 10:36:24', '2023-06-08 10:36:24', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4fa52c85-0349-11ee-a24c-14187762d6e2', 'a7c9584c-c1d6-4233-88b0-834f11ea2867', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:35:35', '2023-06-05 09:35:35', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('4fa836d3-0349-11ee-a24c-14187762d6e2', 'a7c9584c-c1d6-4233-88b0-834f11ea2867', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:35:35', '2023-06-05 09:35:35', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('515f60a9-30df-11ee-8c2a-14187762d6e2', '95b7ec06-54ed-4a2f-91bb-457370226d84', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-08-02 09:50:24', '2023-08-02 09:51:47', '2023-08-02 09:51:47');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('516173f4-30df-11ee-8c2a-14187762d6e2', '95b7ec06-54ed-4a2f-91bb-457370226d84', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-08-02 09:50:24', '2023-08-02 09:50:24', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('51d77f7b-0441-11ee-a24c-14187762d6e2', '1f72af6a-5e1b-42f5-812c-8c4c33d160ab', 'YOLANDA', 'handschoen', 'masker', '', '', 'sepatu_boot', 'penutup_kepala', 'Lupa pakai', '2023-06-06 15:10:55', '2023-06-06 15:10:55', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('53460405-05ad-11ee-a24c-14187762d6e2', '86cde79c-09c5-4c11-8815-ef3251d72cc3', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-06-08 10:36:36', '2023-06-08 10:36:36', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('5467e060-043f-11ee-a24c-14187762d6e2', '1f72af6a-5e1b-42f5-812c-8c4c33d160ab', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-06-06 14:56:40', '2023-06-06 14:56:40', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('54e80693-0349-11ee-a24c-14187762d6e2', '43c4afcd-3a8e-419c-9cf0-6d232a14d0a4', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:35:44', '2023-06-05 09:35:44', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('59a8370d-08eb-11ee-a24c-14187762d6e2', '5c067214-84d0-4224-ba3c-2b6046073af0', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', '', '', '2023-06-12 13:38:12', '2023-06-12 13:38:12', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('59a8448e-08eb-11ee-a24c-14187762d6e2', '5c067214-84d0-4224-ba3c-2b6046073af0', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-12 13:38:12', '2023-06-12 13:38:12', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('6300438f-036e-11ee-a24c-14187762d6e2', 'be3278b5-b9aa-46a1-a071-6426425ea021', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 14:00:59', '2023-06-05 14:24:31', '2023-06-05 14:24:31');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('69c25435-0349-11ee-a24c-14187762d6e2', '91b2c251-bd4f-480c-89d9-560911443a48', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:36:19', '2023-06-05 09:36:19', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('69d13f16-0349-11ee-a24c-14187762d6e2', '91b2c251-bd4f-480c-89d9-560911443a48', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:36:19', '2023-06-05 09:36:19', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('71408720-0349-11ee-a24c-14187762d6e2', '2ec43326-599b-49db-88f2-14ee06fdc93e', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:36:31', '2023-06-06 16:05:43', '2023-06-06 16:05:43');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('7154ac50-0349-11ee-a24c-14187762d6e2', '2ec43326-599b-49db-88f2-14ee06fdc93e', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 09:36:31', '2023-06-05 09:36:31', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('7a5c3d28-0e63-11ee-8009-14187762d6e2', 'e3c15ae0-3a22-4ae5-943b-71d49c99b86f', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 12:38:20', '2023-06-19 12:38:20', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('7a600e3e-0e63-11ee-8009-14187762d6e2', 'e3c15ae0-3a22-4ae5-943b-71d49c99b86f', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 12:38:20', '2023-06-19 13:19:48', '2023-06-19 13:19:48');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('82abc49a-30df-11ee-8c2a-14187762d6e2', '95b7ec06-54ed-4a2f-91bb-457370226d84', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-08-02 09:51:47', '2023-08-02 09:51:47', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('8791803d-0441-11ee-a24c-14187762d6e2', '84a7e1c4-3252-4c17-83d0-a1f9ec5bd2a7', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-06 15:12:25', '2023-06-06 16:05:28', '2023-06-06 16:05:28');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('87a7e367-0a61-11ee-8009-14187762d6e2', '65f4c831-3843-45e5-9479-f9b556c42315', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-14 10:14:14', '2023-06-19 13:52:50', '2023-06-19 13:52:50');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('87ad10c6-0a61-11ee-8009-14187762d6e2', '65f4c831-3843-45e5-9479-f9b556c42315', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-14 10:14:14', '2023-06-19 13:52:50', '2023-06-19 13:52:50');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('8d78dd66-0e6a-11ee-8009-14187762d6e2', '98db58c4-947f-4600-abd2-a8a59e652c50', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:28:58', '2023-06-19 13:51:43', '2023-06-19 13:51:43');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('8d78f352-0e6a-11ee-8009-14187762d6e2', '98db58c4-947f-4600-abd2-a8a59e652c50', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:28:58', '2023-06-19 13:28:58', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('91379465-0a61-11ee-8009-14187762d6e2', '65f4c831-3843-45e5-9479-f9b556c42315', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-14 10:14:30', '2023-06-19 13:29:53', '2023-06-19 13:29:53');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('96236a17-1950-11ee-8009-14187762d6e2', '2fe1e134-ed6a-4577-b547-4e1806451649', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 10:21:01', '2023-07-03 10:21:01', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('97a00d12-0e69-11ee-8009-14187762d6e2', '438d9bec-cb1c-4efc-bbcd-a2eb0850511b', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:22:06', '2023-06-19 13:22:06', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('97a1d3b4-0e69-11ee-8009-14187762d6e2', '438d9bec-cb1c-4efc-bbcd-a2eb0850511b', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:22:06', '2023-06-19 13:24:40', '2023-06-19 13:24:40');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('9e953999-0371-11ee-a24c-14187762d6e2', '4339bcc6-084f-4899-a6a4-bff8e0422459', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-05 14:24:07', '2023-06-05 14:24:24', '2023-06-05 14:24:24');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('9e9a7d9f-0371-11ee-a24c-14187762d6e2', '4339bcc6-084f-4899-a6a4-bff8e0422459', 'MEGA', 'handschoen', 'masker', 'apron', '', '', '', 'lupa bawa', '2023-06-05 14:24:07', '2023-06-05 14:24:24', '2023-06-05 14:24:24');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('9f758ce1-0e6a-11ee-8009-14187762d6e2', '1ff994db-be2e-4514-89c6-916f1a4cde7f', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:29:28', '2023-06-19 13:29:28', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('af78c3fd-04da-11ee-a24c-14187762d6e2', '8525a633-5371-4a00-85ce-3a09618edbb4', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-07 09:28:46', '2023-06-07 11:05:16', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('af7b1ac0-04da-11ee-a24c-14187762d6e2', '8525a633-5371-4a00-85ce-3a09618edbb4', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-07 09:28:46', '2023-06-07 11:05:16', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('b836d6d3-194d-11ee-8009-14187762d6e2', '7632d8fe-b9b7-4985-adfc-50a92df15313', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 10:00:30', '2023-07-03 10:00:30', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('bb282866-0e6d-11ee-8009-14187762d6e2', '98db58c4-947f-4600-abd2-a8a59e652c50', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 13:51:43', '2023-06-19 13:51:43', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('bf4f0c88-1947-11ee-8009-14187762d6e2', '2db70580-46b5-481c-a4e1-af6a5df5f2e0', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-07-03 09:17:45', '2023-07-03 09:23:13', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('bf51c7d9-1947-11ee-8009-14187762d6e2', '2db70580-46b5-481c-a4e1-af6a5df5f2e0', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-07-03 09:17:45', '2023-07-03 09:23:13', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('c0d94b1a-0441-11ee-a24c-14187762d6e2', '608c0844-b785-42e5-b212-a196f1a5b803', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-06 15:14:01', '2023-06-06 15:14:01', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('c653fcec-08eb-11ee-a24c-14187762d6e2', '43c4afcd-3a8e-419c-9cf0-6d232a14d0a4', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-12 13:41:14', '2023-06-12 13:41:14', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('c6838823-194d-11ee-8009-14187762d6e2', 'fd98c86a-ee6d-43c6-8959-ac4f544f4837', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 10:00:54', '2023-07-03 10:00:54', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('c6839dda-194d-11ee-8009-14187762d6e2', 'fd98c86a-ee6d-43c6-8959-ac4f544f4837', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 10:00:54', '2023-07-03 10:00:54', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('cdd9a6bf-04dd-11ee-a24c-14187762d6e2', 'cc25294c-82a1-46cb-930a-7bb561eeee4b', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-07 09:51:05', '2023-06-08 09:26:42', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('ce4bb3eb-0e5e-11ee-8009-14187762d6e2', '1ff994db-be2e-4514-89c6-916f1a4cde7f', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 12:04:53', '2023-06-19 12:05:20', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('ce4be14a-0e5e-11ee-8009-14187762d6e2', '1ff994db-be2e-4514-89c6-916f1a4cde7f', 'DEBORA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-19 12:04:53', '2023-06-19 12:05:20', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('d4132ff4-194d-11ee-8009-14187762d6e2', '67bb8f9d-0bc2-4d71-ac73-1810b857e7e4', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 10:01:17', '2023-07-03 10:01:17', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('d4134286-194d-11ee-8009-14187762d6e2', '67bb8f9d-0bc2-4d71-ac73-1810b857e7e4', 'MEGA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-07-03 10:01:17', '2023-07-03 10:01:17', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('ed621ace-0350-11ee-a24c-14187762d6e2', '89685429-bb9f-4e33-adbe-fcc580af8731', 'DEBORA', 'handschoen', 'masker', 'apron', '', '', '', 'Lupa pakai', '2023-06-05 10:30:06', '2023-06-05 13:18:43', '2023-06-05 13:18:43');
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('f0f2b444-0448-11ee-a24c-14187762d6e2', '84a7e1c4-3252-4c17-83d0-a1f9ec5bd2a7', 'AINI', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', '', '2023-06-06 16:05:28', '2023-06-06 16:05:28', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('f3deaa2e-0e69-11ee-8009-14187762d6e2', '438d9bec-cb1c-4efc-bbcd-a2eb0850511b', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-06-19 13:24:40', '2023-06-19 13:24:40', NULL);
INSERT INTO `cssd_kepatuhan_apd_detail` VALUES ('f77fa2b8-194c-11ee-8009-14187762d6e2', '2fe1e134-ed6a-4577-b547-4e1806451649', 'YOLANDA', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'lengkap', '2023-07-03 09:55:07', '2023-07-03 09:55:07', NULL);

SET FOREIGN_KEY_CHECKS = 1;
