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

 Date: 09/10/2023 08:50:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cssd_monitoring_mesin_steam_verifikasi
-- ----------------------------
DROP TABLE IF EXISTS `cssd_monitoring_mesin_steam_verifikasi`;
CREATE TABLE `cssd_monitoring_mesin_steam_verifikasi`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `id_monitoring_mesin_steam` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT uuid,
  `waktu_keluar_alat` datetime NOT NULL,
  `data_print` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `indikator_eksternal` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `indikator_internal` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `indikator_biologi` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_petugas_verifikator` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hasil_verifikasi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_verifmonitoringsteam_idmonitoringsteam_monitoringsteam_id`(`id_monitoring_mesin_steam`) USING BTREE,
  INDEX `fk_petugasmonitoringsteam_idverifikator_pegawai_nik`(`id_petugas_verifikator`) USING BTREE,
  CONSTRAINT `fk_petugasmonitoringsteam_idverifikator_pegawai_nik` FOREIGN KEY (`id_petugas_verifikator`) REFERENCES `pegawai` (`nik`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_verifmonitoringsteam_idmonitoringsteam_monitoringsteam_id` FOREIGN KEY (`id_monitoring_mesin_steam`) REFERENCES `cssd_monitoring_mesin_steam` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cssd_monitoring_mesin_steam_verifikasi
-- ----------------------------
INSERT INTO `cssd_monitoring_mesin_steam_verifikasi` VALUES ('5584a861-1111-4540-9462-a2a08028ec9f', 'a389ebc1-872b-4519-be23-6268fbe103cd', '2023-08-14 10:00:00', '1693369679_df42b4b1ac7bc657380f.jpg', '1693369679_ba05477706eba5e64e6f.jpg', '1693369679_96f42a39ca5ecb183921.jpg', '1693369679_cef831594ebdc9c7720f.jpg', 'DEBORA', 'Passed-Steril', '2023-08-30 11:27:59', '2023-08-30 11:27:59', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_verifikasi` VALUES ('94c26636-a21a-43a0-9fc7-2a7fb4a3b4a4', '53a62a27-cf50-4870-ba90-924c1393a1a0', '2023-08-09 09:30:00', '1691549742_7da76f96938937db2fc6.jpg', '1691549742_b0f12f83cbc54154a101.jpg', '1691549742_4292c9135f03937d90c9.jpg', '1691549742_7745350a7868c4690e2d.jpg', 'MEGA', 'Passed-Steril', '2023-08-09 09:55:42', '2023-08-09 09:55:42', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_verifikasi` VALUES ('975f8d2c-49d1-4a4d-911e-e6c961f65d36', '04f0fc9a-7739-456d-9098-0dec2e119a12', '2023-09-21 11:30:00', '1695271102_67da0043798853f7fd81.jpg', '1695271102_0b702ec429d1effc5280.jpg', '1695271102_d9ba3ebd39633de37acf.jpg', '1695271102_911687c6d9095963ea72.jpg', 'MEGA', 'Passed-Steril', '2023-09-21 11:38:22', '2023-09-21 11:38:22', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_verifikasi` VALUES ('c4fa8b0b-51ab-4abb-b598-e62b5e79691a', '41f87dd7-0017-4eb6-a01d-533ca2526ccf', '2023-08-09 09:30:00', '1691549913_c5555c1cde9454c3013b.jpg', '1691549913_6202994453bc60b82d10.jpg', '1691549913_cf5f0b80af567d753633.jpg', '1691549913_dc78917044cdba1e673b.jpg', 'AINI', 'Passed-Steril', '2023-08-09 09:58:33', '2023-08-09 09:58:33', NULL);
INSERT INTO `cssd_monitoring_mesin_steam_verifikasi` VALUES ('f3dcec90-b202-45dd-b9e3-37aa83f6fc3d', 'a7889a1a-4244-427b-95e8-523369ef8b60', '2023-08-15 09:00:00', '1692068785_ca7e7c2e82fbb2912bc2.jpg', '1692068785_df40f4031bf74924031a.jpg', '1692068785_2315c8cc006322b32403.jpg', '1692068785_3f2c5f686214df770253.jpg', 'DEBORA', 'Passed-Steril', '2023-08-15 10:06:25', '2023-08-15 10:07:22', NULL);

SET FOREIGN_KEY_CHECKS = 1;
