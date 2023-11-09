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

 Date: 09/10/2023 08:48:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_kepatuhan_apd
-- ----------------------------
DROP TABLE IF EXISTS `cssd_kepatuhan_apd`;
CREATE TABLE `cssd_kepatuhan_apd`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `tanggal_cek` date NULL DEFAULT NULL,
  `shift` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_suhukelembapan_pegawai_nik`(`shift`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_kepatuhan_apd
-- ----------------------------
INSERT INTO `cssd_kepatuhan_apd` VALUES ('133997d9-0781-4150-9dd3-30245fb497ff', '2023-06-16', 'pagi', '2023-06-19 13:40:51', '2023-06-19 13:41:08', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('1f72af6a-5e1b-42f5-812c-8c4c33d160ab', '2023-06-05', 'pagi', '2023-06-05 09:19:19', '2023-06-06 15:10:55', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('1ff994db-be2e-4514-89c6-916f1a4cde7f', '2023-06-15', 'pagi', '2023-06-19 12:04:53', '2023-06-19 13:29:28', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('2db70580-46b5-481c-a4e1-af6a5df5f2e0', '2023-07-03', 'pagi', '2023-07-03 09:17:45', '2023-07-03 09:23:13', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('2ec43326-599b-49db-88f2-14ee06fdc93e', '2023-06-03', 'sore', '2023-06-05 09:36:31', '2023-06-12 11:14:35', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('2fe1e134-ed6a-4577-b547-4e1806451649', '2023-07-02', 'pagi', '2023-07-03 09:41:16', '2023-07-03 10:21:01', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('4339bcc6-084f-4899-a6a4-bff8e0422459', '2023-06-05', 'sore', '2023-06-05 14:24:07', '2023-06-05 14:24:24', '2023-06-05 14:24:24');
INSERT INTO `cssd_kepatuhan_apd` VALUES ('438d9bec-cb1c-4efc-bbcd-a2eb0850511b', '2023-06-17', 'sore', '2023-06-19 13:22:06', '2023-06-19 13:24:40', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('43c4afcd-3a8e-419c-9cf0-6d232a14d0a4', '2023-06-02', 'sore', '2023-06-05 09:35:44', '2023-06-12 13:41:14', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('5c067214-84d0-4224-ba3c-2b6046073af0', '2023-06-12', 'pagi', '2023-06-12 13:37:40', '2023-06-12 13:38:12', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('608c0844-b785-42e5-b212-a196f1a5b803', '2023-06-01', 'sore', '2023-06-05 09:35:25', '2023-06-06 15:14:01', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('608c0844-b785-42e5-b212-a196f1a5b805', '2023-06-01', 'sore', '2023-06-05 09:35:25', '2023-06-12 13:57:49', '2023-06-12 13:57:49');
INSERT INTO `cssd_kepatuhan_apd` VALUES ('65f4c831-3843-45e5-9479-f9b556c42315', '2023-06-14', 'pagi', '2023-06-14 10:14:14', '2023-06-19 13:52:50', '2023-06-19 13:52:50');
INSERT INTO `cssd_kepatuhan_apd` VALUES ('661eb2f0-1922-41b7-a28f-947b9b09457a', '2023-06-01', 'pagi', '2023-06-05 09:35:15', '2023-06-12 10:59:20', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('67bb8f9d-0bc2-4d71-ac73-1810b857e7e4', '2023-07-01', 'sore', '2023-07-03 10:01:16', '2023-07-03 10:01:16', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('7632d8fe-b9b7-4985-adfc-50a92df15313', '2023-07-02', 'sore', '2023-07-03 09:55:28', '2023-07-03 10:00:30', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('84a7e1c4-3252-4c17-83d0-a1f9ec5bd2a7', '2023-06-06', 'pagi', '2023-06-06 09:48:26', '2023-06-06 16:05:28', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('8525a633-5371-4a00-85ce-3a09618edbb4', '2023-06-07', 'pagi', '2023-06-07 09:28:46', '2023-06-07 11:05:16', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('86cde79c-09c5-4c11-8815-ef3251d72cc3', '2023-06-08', 'pagi', '2023-06-08 10:36:36', '2023-06-08 10:36:36', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('89685429-bb9f-4e33-adbe-fcc580af8731', '2023-06-05', 'pagi', '2023-06-05 10:30:06', '2023-06-05 13:18:43', '2023-06-05 13:18:43');
INSERT INTO `cssd_kepatuhan_apd` VALUES ('91b2c251-bd4f-480c-89d9-560911443a48', '2023-06-03', 'pagi', '2023-06-05 09:36:19', '2023-06-05 09:36:19', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('95b7ec06-54ed-4a2f-91bb-457370226d84', '2023-08-02', 'pagi', '2023-08-02 09:50:24', '2023-08-02 09:51:47', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('98db58c4-947f-4600-abd2-a8a59e652c50', '2023-06-19', 'pagi', '2023-06-19 13:28:58', '2023-06-19 13:51:43', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('a7c9584c-c1d6-4233-88b0-834f11ea2867', '2023-06-02', 'pagi', '2023-06-05 09:35:35', '2023-06-05 09:35:35', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('be3278b5-b9aa-46a1-a071-6426425ea021', '2023-06-05', 'pagi', '2023-06-05 14:00:59', '2023-06-05 14:24:31', '2023-06-05 14:24:31');
INSERT INTO `cssd_kepatuhan_apd` VALUES ('cc25294c-82a1-46cb-930a-7bb561eeee4b', '2023-06-06', 'sore', '2023-06-07 09:51:05', '2023-06-08 09:26:42', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('e3c15ae0-3a22-4ae5-943b-71d49c99b86f', '2023-06-18', 'pagi', '2023-06-19 12:38:20', '2023-06-19 13:25:15', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('e40d2a87-0c03-4ff1-ba74-6a7ef177fc89', '2023-06-05', 'sore', '2023-06-05 09:34:56', '2023-06-05 09:34:56', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('f6067deb-0674-429f-b602-2db80e4bdc2e', '2023-06-07', 'sore', '2023-06-08 10:36:24', '2023-06-08 10:36:24', NULL);
INSERT INTO `cssd_kepatuhan_apd` VALUES ('fd98c86a-ee6d-43c6-8959-ac4f544f4837', '2023-07-01', 'pagi', '2023-07-03 10:00:54', '2023-07-03 10:00:54', NULL);

SET FOREIGN_KEY_CHECKS = 1;
