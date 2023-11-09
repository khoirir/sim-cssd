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

 Date: 09/10/2023 08:52:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_satuan_set_alat
-- ----------------------------
DROP TABLE IF EXISTS `cssd_satuan_set_alat`;
CREATE TABLE `cssd_satuan_set_alat`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `kode_satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama_satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_satuan_set_alat
-- ----------------------------
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c12026-3596-11ee-8c2a-14187762d6e2', 'Amp', 'Ampul', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c22559-3596-11ee-8c2a-14187762d6e2', 'Btl', 'Botol', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c2b1ff-3596-11ee-8c2a-14187762d6e2', 'Box', 'Box', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c33f37-3596-11ee-8c2a-14187762d6e2', 'Cm', 'Centimeter', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c3e08c-3596-11ee-8c2a-14187762d6e2', 'Fls', 'Flask', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c47e1c-3596-11ee-8c2a-14187762d6e2', 'Gln', 'Galon', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c5ad32-3596-11ee-8c2a-14187762d6e2', 'Klg', 'Kaleng', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c62b06-3596-11ee-8c2a-14187762d6e2', 'Kapl', 'Kaplet', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c694db-3596-11ee-8c2a-14187762d6e2', 'Cap', 'Kapsul', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c789e2-3596-11ee-8c2a-14187762d6e2', 'Lbr', 'Lembar', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c81b24-3596-11ee-8c2a-14187762d6e2', 'ml', 'Mililiter', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c88ac7-3596-11ee-8c2a-14187762d6e2', 'Patc', 'Patch', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c8f41d-3596-11ee-8c2a-14187762d6e2', 'Pen', 'Pen insulin', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01c9665c-3596-11ee-8c2a-14187762d6e2', 'Pcs', 'Pieces', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01ca9163-3596-11ee-8c2a-14187762d6e2', 'Roll', 'Roll', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01cb2049-3596-11ee-8c2a-14187762d6e2', 'Sach', 'Sachet', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01cbfed1-3596-11ee-8c2a-14187762d6e2', 'Stri', 'Strip', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01cd1c53-3596-11ee-8c2a-14187762d6e2', 'Srg', 'Syringe', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01cd915f-3596-11ee-8c2a-14187762d6e2', 'Tab', 'Tablet', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01ce09d4-3596-11ee-8c2a-14187762d6e2', 'Tube', 'Tube', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('01ce743a-3596-11ee-8c2a-14187762d6e2', 'Vial', 'Vial', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('5f0due0d-34ce-11ee-8c2a-14167563d6e1', 'Set', 'Set', '2023-08-07 10:36:13', '2023-08-07 10:36:17', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('6e0dae6d-37ce-13ee-8c2a-14167589d6e2', 'Poch', 'Poch', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);
INSERT INTO `cssd_satuan_set_alat` VALUES ('7c7837a6-738c-4f51-bcd2-8d2c839a13ff', 'amp2', 'wqe asds', '2023-08-08 13:07:42', '2023-08-08 13:45:32', '2023-08-08 13:45:32');
INSERT INTO `cssd_satuan_set_alat` VALUES ('7e0dae9d-34ce-11ee-8c2a-14187762d6e2', 'Linen', 'Linen', '2023-08-07 10:36:13', '2023-08-07 10:36:13', NULL);

SET FOREIGN_KEY_CHECKS = 1;
