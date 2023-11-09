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

 Date: 09/10/2023 08:52:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_uji_larutan_dtt_alkacyd
-- ----------------------------
DROP TABLE IF EXISTS `cssd_uji_larutan_dtt_alkacyd`;
CREATE TABLE `cssd_uji_larutan_dtt_alkacyd`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT uuid,
  `tanggal_uji` date NULL DEFAULT NULL,
  `id_petugas` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `metracid_1_ml` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alkacid_10_ml` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `hasil_warna` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `upload_larutan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_uji_larutan_id_petugas_pegawai_nik`(`id_petugas`) USING BTREE,
  CONSTRAINT `fk_uji_larutan_id_petugas_pegawai_nik` FOREIGN KEY (`id_petugas`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_uji_larutan_dtt_alkacyd
-- ----------------------------
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('18768930-10fd-46f4-a1c3-8760d5237825', '2023-06-14', 'MEGA', 'checked', 'checked', 'ungu', '1686804294_707383a35e765dfd0ef8.jpeg', 'Passed', '2023-06-14 15:08:41', '2023-06-19 14:31:01', '2023-06-19 14:31:01');
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('277e2ca5-329c-477f-a673-f1944c02748d', '2023-07-03', 'AINI', 'checked', 'checked', 'ungu', '1689221588_8b39beb0ea00d4ec1fb3.jpeg', 'Passed', '2023-07-03 11:06:34', '2023-07-13 11:13:08', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('3ee45fec-0348-4093-b6de-f91e9d24c662', '2023-06-19', 'MEGA', 'checked', 'checked', 'pink', '1687158847_ad624d7121154f9e3225.jpeg', 'Failed', '2023-06-19 14:14:07', '2023-06-19 14:20:26', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('51dcc1cd-7141-4eed-a0f4-bd1046c8c6d4', '2023-06-14', 'DEBORA', 'checked', 'checked', 'ungu', '1686804398_93d990dab9bbcebe36a9.jpg', 'Passed', '2023-06-14 15:06:41', '2023-06-19 14:32:57', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('6f7aed4f-8278-43d6-8a17-5d2e30f23ee4', '2023-07-02', 'DEBORA', 'checked', 'checked', 'ungu', '1689221608_e93aebd5225f32edb43d.jpeg', 'Passed', '2023-07-03 11:06:49', '2023-07-13 11:13:28', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('a5607b14-9a83-49b6-90fe-118aef01e85d', '2023-07-13', 'YOLANDA', 'checked', 'checked', 'pink', '1689221865_f6928f658349f0a92830.jpeg', 'Failed', '2023-07-13 11:17:45', '2023-07-13 11:17:45', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('d3189823-7099-4bf3-8459-0059cf12e658', '2023-06-22', 'DEBORA', 'checked', 'checked', 'ungu', '1687412211_98a1c5966d2b33afccff.jpeg', 'Passed', '2023-06-22 12:36:51', '2023-06-22 12:37:02', '2023-06-22 12:37:02');
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('d3ffc605-3f8f-4af5-a46a-7585ab928101', '2023-06-15', 'DEBORA', 'checked', 'checked', 'ungu', '1686804377_25cdce2bf2589b8fe994.jpg', 'Passed', '2023-06-15 11:19:55', '2023-06-15 11:46:17', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('d5d8de34-ef57-456f-bf00-5c1849a940d0', '2023-06-14', 'YOLANDA', 'checked', 'checked', 'ungu', '1686804351_8bd19bdb5153b46e61c0.jpg', 'Passed', '2023-06-14 15:13:25', '2023-06-15 11:45:51', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('ec2efc1e-15f5-47fa-804b-ea182e0389e8', '2023-08-02', 'DEBORA', 'checked', 'checked', 'ungu', '1690945011_d6098328a3c84cd2a081.jpeg', 'Passed', '2023-08-02 09:56:51', '2023-08-02 09:56:51', NULL);
INSERT INTO `cssd_uji_larutan_dtt_alkacyd` VALUES ('f7612066-36ff-45ff-ab42-df05b3c15fb5', '2023-06-14', 'DEBORA', 'checked', 'checked', 'ungu', '1686730889_92ad051c58ef04f84614.png', 'Passed', '2023-06-14 15:21:29', '2023-06-19 14:29:29', '2023-06-19 14:29:29');

SET FOREIGN_KEY_CHECKS = 1;
