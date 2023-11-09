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

 Date: 09/10/2023 08:51:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_suhu_kelembapan
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_suhu_kelembapan`;
CREATE TABLE `cssd_monitoring_suhu_kelembapan`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `tgl_catat` date NULL DEFAULT NULL,
  `id_petugas` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `suhu` int NULL DEFAULT NULL,
  `kelembapan` int NULL DEFAULT NULL,
  `tindakan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'disabled',
  `hasil_tindakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_suhukelembapan_pegawai_nik`(`id_petugas`) USING BTREE,
  CONSTRAINT `fk_suhukelembapan_pegawai_nik` FOREIGN KEY (`id_petugas`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_suhu_kelembapan
-- ----------------------------
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('00ce0fb8-6c33-41a3-8024-0c7fe264b669', '2023-06-19', 'YOLANDA', 25, 68, 'disabled', NULL, '2023-06-19 07:40:49', '2023-06-19 09:44:48', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('03a03bb4-2328-4e37-8f10-3c0beed84803', '2023-06-01', 'AINI', 21, 45, '', 'hasil dan tindakan sudah dilaporkan', '2023-06-05 09:30:51', '2023-06-16 15:24:06', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('1059d39f-1245-41cb-ad51-610506dbb919', '2023-06-10', 'DEBORA', 30, 56, 'disabled', NULL, '2023-06-12 09:10:41', '2023-06-16 14:40:49', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('18d4ecea-6e92-4241-bc8b-85d58f7d794c', '2023-06-16', 'DEBORA', 30, 40, 'disabled', NULL, '2023-06-16 13:53:03', '2023-06-16 14:42:06', '2023-06-16 14:42:06');
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('2314933c-3783-4206-af00-41e6e0ebb824', '2023-07-03', 'MEGA', 23, 46, 'disabled', NULL, '2023-07-03 09:06:21', '2023-07-03 09:11:40', '2023-07-03 09:11:40');
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('28e6eeb1-a8bf-4507-a6c3-5ef8922c6857', '2023-06-02', 'DEBORA', 25, 40, 'disabled', NULL, '2023-06-05 09:26:22', '2023-06-05 09:26:22', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('2ef97168-f611-11ed-a24c-14187762d6e2', '2023-05-19', 'MEGA', 22, 34, '', 'sudah diperbaiki, suhu tetap tidak normal', '2023-05-19 13:50:48', '2023-05-25 09:59:35', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('3122fae6-f1dd-4f8a-80c9-ad4c265a526a', '2023-06-06', 'DEBORA', 23, 45, 'disabled', NULL, '2023-06-07 15:07:34', '2023-06-08 09:42:37', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('319763ce-8dc2-4847-b54a-9120d4c5888f', '2023-07-02', 'DEBORA', 21, 50, '', 'Sudah dilakukan tindakan', '2023-07-03 09:06:10', '2023-07-03 09:13:46', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('3531e5bd-f9fa-11ed-a24c-14187762d6e2', '2023-05-16', 'DEBORA', 25, 65, 'disabled', NULL, '2023-05-24 13:16:30', '2023-05-24 15:58:52', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('35b94664-8125-48f9-b3cd-2645ef5f5a61', '2023-06-12', 'DEBORA', 26, 77, '', 'selesai tindakan', '2023-06-12 09:11:03', '2023-06-19 13:56:48', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('4aaabd65-2e94-4d5c-a74a-305455391002', '2023-07-03', 'MEGA', 25, 40, 'disabled', NULL, '2023-07-03 09:11:51', '2023-07-03 09:11:51', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('58a6c535-3bc6-4088-8f45-8f823cb21af7', '2023-06-11', 'MEGA', 25, 40, 'disabled', NULL, '2023-06-12 09:10:51', '2023-06-12 09:10:51', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('59ea0bd2-b795-4b82-8453-ac51aaeb7c23', '2023-06-13', 'DEBORA', 32, 45, '', NULL, '2023-06-13 12:20:34', '2023-06-15 13:05:17', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('5abf6250-4e91-492c-ad1f-cdf79a4a6d35', '2023-06-17', 'AINI', 30, 65, 'disabled', NULL, '2023-06-19 13:58:19', '2023-06-19 13:58:43', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('6bb1f2b2-f61d-11ed-a24c-14187762d6e2', '2023-05-18', 'DEBORA', 32, 67, '', 'sudah dilaporkan, dan hasilnya tetap.', '2023-05-19 15:18:24', '2023-05-25 10:16:03', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('722618d2-fac8-11ed-a24c-14187762d6e2', '2023-05-01', 'DEBORA', 30, 65, 'disabled', NULL, '2023-05-25 13:52:49', '2023-05-25 13:52:49', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('74bcd09a-f61d-11ed-a24c-14187762d6e2', '2023-05-17', 'AINI', 28, 78, '', NULL, '2023-05-19 15:18:39', '2023-05-25 10:12:26', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('75817dd1-aa31-4205-89c4-362e132467ff', '2023-08-01', 'AINI', 25, 65, 'disabled', NULL, '2023-08-02 09:16:30', '2023-08-02 09:16:30', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('77b60d95-fac8-11ed-a24c-14187762d6e2', '2023-05-02', 'DEBORA', 45, 60, '', NULL, '2023-05-25 13:52:58', '2023-05-25 13:53:19', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('797e7d62-f61d-11ed-a24c-14187762d6e2', '2023-05-11', 'MEGA', 21, 60, 'disabled', NULL, '2023-05-19 15:18:47', '2023-05-23 12:07:05', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('7e82fbac-f9f9-11ed-a24c-14187762d6e2', '2023-05-24', 'MEGA', 20, 70, '', NULL, '2023-05-24 13:11:23', '2023-05-25 10:20:32', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('7f0dac6e-f61d-11ed-a24c-14187762d6e2', '2023-05-15', 'YOLANDA', 25, 75, 'disabled', NULL, '2023-05-19 15:18:57', '2023-05-24 15:58:01', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('83d7ae8f-f1dd-4b64-924f-e8ec4647b704', '2023-06-09', 'MEGA', 25, 65, 'disabled', NULL, '2023-06-09 09:15:05', '2023-06-12 09:04:01', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('8fbf107e-a76e-4c68-bc85-5ebc448e4a28', '2023-06-03', 'MEGA', 30, 70, 'disabled', NULL, '2023-06-05 09:26:34', '2023-06-05 09:26:34', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('9345df7e-3e78-4d79-95c4-e54a5dfdad0f', '2023-06-18', 'YOLANDA', 12, 45, '', NULL, '2023-06-19 13:59:07', '2023-06-19 13:59:07', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('9ad948b4-fb66-11ed-a24c-14187762d6e2', '2023-05-26', 'MEGA', 25, 50, 'disabled', NULL, '2023-05-26 08:44:59', '2023-05-26 08:44:59', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('9b17081c-fac8-11ed-a24c-14187762d6e2', '2023-05-03', 'DEBORA', 20, 70, '', NULL, '2023-05-25 13:53:57', '2023-05-25 13:53:57', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('9ca5ebe6-72a0-4738-a363-de9f6760387f', '2023-06-16', 'DEBORA', 30, 50, 'disabled', NULL, '2023-06-16 14:42:20', '2023-06-16 14:42:20', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('a107acd2-fac8-11ed-a24c-14187762d6e2', '2023-05-04', 'AINI', 32, 45, '', NULL, '2023-05-25 13:54:07', '2023-05-25 13:54:07', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('a462fe13-37ab-41a2-ad1a-884030d732bb', '2023-06-15', 'DEBORA', 25, 45, 'disabled', NULL, '2023-06-19 09:46:25', '2023-06-19 09:46:25', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('a4a38a30-8b59-4a2e-ac64-a63b0253b163', '2023-08-04', 'DEBORA', 17, 38, '', NULL, '2023-08-04 10:32:24', '2023-08-04 10:32:24', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('a6b8af71-fac8-11ed-a24c-14187762d6e2', '2023-05-05', 'YOLANDA', 19, 70, '', NULL, '2023-05-25 13:54:17', '2023-05-25 13:54:17', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('ab23803e-faa6-11ed-a24c-14187762d6e2', '2023-05-25', 'MEGA', 25, 70, 'disabled', NULL, '2023-05-25 09:51:01', '2023-05-25 09:51:53', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('be143c1c-fac8-11ed-a24c-14187762d6e2', '2023-05-06', 'AINI', 22, 40, 'disabled', NULL, '2023-05-25 13:54:56', '2023-05-25 13:54:56', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('c03b924a-7b71-4331-a2eb-6bbab0eaf4b0', '2023-06-08', 'AINI', 30, 50, 'disabled', NULL, '2023-06-08 10:08:52', '2023-06-08 10:08:52', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('c37941ce-6e3a-4ae1-827f-dfa1d336c48e', '2023-07-01', 'AINI', 30, 61, 'disabled', NULL, '2023-07-03 09:06:00', '2023-07-03 09:12:17', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('c479a4ef-fac8-11ed-a24c-14187762d6e2', '2023-05-07', 'MEGA', 23, 45, 'disabled', NULL, '2023-05-25 13:55:07', '2023-05-25 13:55:07', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('c9eb9f13-fac8-11ed-a24c-14187762d6e2', '2023-05-08', 'MEGA', 16, 35, '', NULL, '2023-05-25 13:55:16', '2023-05-25 13:55:16', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('cd0c30c9-84e0-4697-97bf-2aeea1028381', '2023-08-02', 'DEBORA', 30, 70, 'disabled', NULL, '2023-08-02 09:16:41', '2023-08-02 09:16:41', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('ce7c36c4-facf-11ed-a24c-14187762d6e2', '2023-05-21', 'MEGA', 25, 76, '', NULL, '2023-05-25 14:45:30', '2023-05-25 14:45:30', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('cf1f3c36-fac8-11ed-a24c-14187762d6e2', '2023-05-09', 'AINI', 30, 60, 'disabled', NULL, '2023-05-25 13:55:25', '2023-05-25 13:55:25', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('d40e7b8a-fac8-11ed-a24c-14187762d6e2', '2023-05-10', 'AINI', 19, 30, '', NULL, '2023-05-25 13:55:33', '2023-05-25 13:55:33', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('d44bc2eb-f9e5-11ed-a24c-14187762d6e2', '2023-05-20', 'YOLANDA', 40, 65, 'disabled', NULL, '2023-05-24 10:50:37', '2023-05-24 10:52:45', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('d5812cf3-facf-11ed-a24c-14187762d6e2', '2023-05-22', 'DEBORA', 35, 40, '', NULL, '2023-05-25 14:45:42', '2023-05-25 14:45:42', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('e285cef6-536a-4214-aa2d-758c98445b78', '2023-06-05', 'AINI', 31, 65, '', 'Sudah dilakukan tindakan', '2023-06-05 09:26:56', '2023-06-05 09:29:53', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('e5775a3c-fa01-11ed-a24c-14187762d6e2', '2023-05-23', 'DEBORA', 40, 40, '', 'sudah ditindak', '2023-05-24 14:11:32', '2023-05-25 10:07:19', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('e76bd6bd-6694-4d3c-8269-610b718157d0', '2023-06-14', 'AINI', 26, 65, 'disabled', NULL, '2023-06-14 10:12:55', '2023-06-14 10:13:51', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('eab9f967-48c4-486e-8fbb-7af8638743ee', '2023-06-07', 'DEBORA', 30, 50, 'disabled', NULL, '2023-06-07 11:26:07', '2023-06-08 09:42:25', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('ed5e8e1d-facf-11ed-a24c-14187762d6e2', '2023-05-12', 'YOLANDA', 30, 55, 'disabled', NULL, '2023-05-25 14:46:22', '2023-05-25 14:46:22', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('ef608169-b55c-4973-b658-622a69a63e7a', '2023-06-04', 'YOLANDA', 25, 40, 'disabled', NULL, '2023-06-05 09:26:44', '2023-06-05 09:27:16', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('f3d59934-facf-11ed-a24c-14187762d6e2', '2023-05-13', 'YOLANDA', 32, 35, '', NULL, '2023-05-25 14:46:33', '2023-05-25 14:46:33', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('fa007d70-facf-11ed-a24c-14187762d6e2', '2023-05-14', 'DEBORA', 30, 25, '', NULL, '2023-05-25 14:46:43', '2023-05-25 14:46:43', NULL);
INSERT INTO `cssd_monitoring_suhu_kelembapan` VALUES ('fa01be6c-48d2-4995-aee1-cb47f5c5f9f1', '2023-06-01', 'AINI', 25, 70, 'disabled', NULL, '2023-06-05 09:25:56', '2023-06-05 09:30:21', '2023-06-05 09:30:21');

SET FOREIGN_KEY_CHECKS = 1;
