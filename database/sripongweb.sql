/*
 Navicat Premium Dump SQL

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 80039 (8.0.39)
 Source Host           : localhost:3306
 Source Schema         : sripongweb

 Target Server Type    : MySQL
 Target Server Version : 80039 (8.0.39)
 File Encoding         : 65001

 Date: 15/02/2025 10:11:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for activities
-- ----------------------------
DROP TABLE IF EXISTS `activities`;
CREATE TABLE `activities`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `event_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of activities
-- ----------------------------

-- ----------------------------
-- Table structure for gallery_images
-- ----------------------------
DROP TABLE IF EXISTS `gallery_images`;
CREATE TABLE `gallery_images`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `active` tinyint(1) NULL DEFAULT 1,
  `upload_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gallery_images
-- ----------------------------
INSERT INTO `gallery_images` VALUES (1, '67ad9f2e9cba6.jpg', '1', '', 1, '2025-02-13 14:28:46');
INSERT INTO `gallery_images` VALUES (2, '67ad9f2e9d8b4.jpg', '2', '', 1, '2025-02-13 14:28:46');
INSERT INTO `gallery_images` VALUES (3, '67ad9f2e9e6e4.jpg', '3', '', 1, '2025-02-13 14:28:46');
INSERT INTO `gallery_images` VALUES (4, '67ad9f2e9f485.jpg', '4', '', 1, '2025-02-13 14:28:46');

-- ----------------------------
-- Table structure for page_content
-- ----------------------------
DROP TABLE IF EXISTS `page_content`;
CREATE TABLE `page_content`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `section_name`(`section_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of page_content
-- ----------------------------
INSERT INTO `page_content` VALUES (1, 'hero', 'ยินดีต้อนรับสู่ศรีพงษ์ปาร์ค', 'สวนแห่งความสุขสำหรับทุกครอบครัว', NULL, '2025-02-13 12:02:15');
INSERT INTO `page_content` VALUES (6, 'features', NULL, '[\r\n    {\r\n        \"icon\": \"📚\",\r\n        \"title\": \"ร้านหนังสือ & เครื่องเขียน\",\r\n        \"description\": \"ครบครันด้วยหนังสือและอุปกรณ์การเรียนคุณภาพ\"\r\n    },\r\n    {\r\n        \"icon\": \"🎨\",\r\n        \"title\": \"กิจกรรมสร้างสรรค์\",\r\n        \"description\": \"เวิร์คช็อปและกิจกรรมสนุกๆ สำหรับทุกวัย\"\r\n    },\r\n    {\r\n        \"icon\": \"🎁\",\r\n        \"title\": \"โปรโมชั่นพิเศษ\",\r\n        \"description\": \"ข้อเสนอสุดพิเศษและส่วนลดมากมาย\"\r\n    }\r\n]', NULL, '2025-02-13 12:31:35');

-- ----------------------------
-- Table structure for points_transactions
-- ----------------------------
DROP TABLE IF EXISTS `points_transactions`;
CREATE TABLE `points_transactions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `points` int NOT NULL,
  `transaction_type` enum('earn','redeem') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `points_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of points_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for promotion_images
-- ----------------------------
DROP TABLE IF EXISTS `promotion_images`;
CREATE TABLE `promotion_images`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `start_date` datetime NULL DEFAULT NULL,
  `end_date` datetime NULL DEFAULT NULL,
  `active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of promotion_images
-- ----------------------------
INSERT INTO `promotion_images` VALUES (3, '67ada08701278.webp', NULL, 'Valentine Love Shot', 'Valentine Love Shot', '2025-02-13 00:00:00', '2025-02-14 00:00:00', 1, '2025-02-13 14:34:31');

-- ----------------------------
-- Table structure for promotion_registrations
-- ----------------------------
DROP TABLE IF EXISTS `promotion_registrations`;
CREATE TABLE `promotion_registrations`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `phone`(`phone` ASC, `created_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of promotion_registrations
-- ----------------------------

-- ----------------------------
-- Table structure for promotions
-- ----------------------------
DROP TABLE IF EXISTS `promotions`;
CREATE TABLE `promotions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `is_new` tinyint(1) NULL DEFAULT 1,
  `active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 184 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of promotions
-- ----------------------------
INSERT INTO `promotions` VALUES (1, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:24:10', '2025-02-13 13:24:10');
INSERT INTO `promotions` VALUES (2, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:24:10', '2025-02-13 13:24:10');
INSERT INTO `promotions` VALUES (3, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:25:07', '2025-02-13 13:25:07');
INSERT INTO `promotions` VALUES (4, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:25:07', '2025-02-13 13:25:07');
INSERT INTO `promotions` VALUES (5, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:26:16', '2025-02-13 13:26:16');
INSERT INTO `promotions` VALUES (6, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:26:51', '2025-02-13 13:26:51');
INSERT INTO `promotions` VALUES (7, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:27:48', '2025-02-13 13:27:48');
INSERT INTO `promotions` VALUES (8, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:27:48', '2025-02-13 13:27:48');
INSERT INTO `promotions` VALUES (9, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:28:01', '2025-02-13 13:28:01');
INSERT INTO `promotions` VALUES (10, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:28:01', '2025-02-13 13:28:01');
INSERT INTO `promotions` VALUES (11, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:29:27', '2025-02-13 13:29:27');
INSERT INTO `promotions` VALUES (12, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:29:36', '2025-02-13 13:29:36');
INSERT INTO `promotions` VALUES (13, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:29:37', '2025-02-13 13:29:37');
INSERT INTO `promotions` VALUES (14, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:30:44', '2025-02-13 13:30:44');
INSERT INTO `promotions` VALUES (15, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:30:46', '2025-02-13 13:30:46');
INSERT INTO `promotions` VALUES (16, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:33:51', '2025-02-13 13:33:51');
INSERT INTO `promotions` VALUES (17, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:34:00', '2025-02-13 13:34:00');
INSERT INTO `promotions` VALUES (18, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:34:10', '2025-02-13 13:34:10');
INSERT INTO `promotions` VALUES (19, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:34:19', '2025-02-13 13:34:19');
INSERT INTO `promotions` VALUES (20, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:34:51', '2025-02-13 13:34:51');
INSERT INTO `promotions` VALUES (21, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:36:17', '2025-02-13 13:36:17');
INSERT INTO `promotions` VALUES (22, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:36:26', '2025-02-13 13:36:26');
INSERT INTO `promotions` VALUES (23, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:36:27', '2025-02-13 13:36:27');
INSERT INTO `promotions` VALUES (24, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:37:39', '2025-02-13 13:37:39');
INSERT INTO `promotions` VALUES (25, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:38:45', '2025-02-13 13:38:45');
INSERT INTO `promotions` VALUES (26, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:39:16', '2025-02-13 13:39:16');
INSERT INTO `promotions` VALUES (27, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:39:55', '2025-02-13 13:39:55');
INSERT INTO `promotions` VALUES (28, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:40:47', '2025-02-13 13:40:47');
INSERT INTO `promotions` VALUES (29, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:41:33', '2025-02-13 13:41:33');
INSERT INTO `promotions` VALUES (30, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:42:08', '2025-02-13 13:42:08');
INSERT INTO `promotions` VALUES (31, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:42:10', '2025-02-13 13:42:10');
INSERT INTO `promotions` VALUES (32, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 13:42:52', '2025-02-13 13:42:52');
INSERT INTO `promotions` VALUES (33, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:06:04', '2025-02-13 14:06:04');
INSERT INTO `promotions` VALUES (34, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:08:29', '2025-02-13 14:08:29');
INSERT INTO `promotions` VALUES (35, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:09:03', '2025-02-13 14:09:03');
INSERT INTO `promotions` VALUES (36, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:10:02', '2025-02-13 14:10:02');
INSERT INTO `promotions` VALUES (37, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:10:14', '2025-02-13 14:10:14');
INSERT INTO `promotions` VALUES (38, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:12:09', '2025-02-13 14:12:09');
INSERT INTO `promotions` VALUES (39, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:13:36', '2025-02-13 14:13:36');
INSERT INTO `promotions` VALUES (40, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:14:03', '2025-02-13 14:14:03');
INSERT INTO `promotions` VALUES (41, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:16:09', '2025-02-13 14:16:09');
INSERT INTO `promotions` VALUES (42, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:18:10', '2025-02-13 14:18:10');
INSERT INTO `promotions` VALUES (43, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:20:04', '2025-02-13 14:20:04');
INSERT INTO `promotions` VALUES (44, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:22:25', '2025-02-13 14:22:25');
INSERT INTO `promotions` VALUES (45, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:14', '2025-02-13 14:23:14');
INSERT INTO `promotions` VALUES (46, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:15', '2025-02-13 14:23:15');
INSERT INTO `promotions` VALUES (47, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:27', '2025-02-13 14:23:27');
INSERT INTO `promotions` VALUES (48, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:28', '2025-02-13 14:23:28');
INSERT INTO `promotions` VALUES (49, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:43', '2025-02-13 14:23:43');
INSERT INTO `promotions` VALUES (50, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:44', '2025-02-13 14:23:44');
INSERT INTO `promotions` VALUES (51, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:56', '2025-02-13 14:23:56');
INSERT INTO `promotions` VALUES (52, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:23:57', '2025-02-13 14:23:57');
INSERT INTO `promotions` VALUES (53, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:13', '2025-02-13 14:24:13');
INSERT INTO `promotions` VALUES (54, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:14', '2025-02-13 14:24:14');
INSERT INTO `promotions` VALUES (55, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:18', '2025-02-13 14:24:18');
INSERT INTO `promotions` VALUES (56, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:20', '2025-02-13 14:24:20');
INSERT INTO `promotions` VALUES (57, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:22', '2025-02-13 14:24:22');
INSERT INTO `promotions` VALUES (58, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:25', '2025-02-13 14:24:25');
INSERT INTO `promotions` VALUES (59, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:36', '2025-02-13 14:24:36');
INSERT INTO `promotions` VALUES (60, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:46', '2025-02-13 14:24:46');
INSERT INTO `promotions` VALUES (61, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:24:52', '2025-02-13 14:24:52');
INSERT INTO `promotions` VALUES (62, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:25:18', '2025-02-13 14:25:18');
INSERT INTO `promotions` VALUES (63, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:25:20', '2025-02-13 14:25:20');
INSERT INTO `promotions` VALUES (64, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:25:22', '2025-02-13 14:25:22');
INSERT INTO `promotions` VALUES (65, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:25:23', '2025-02-13 14:25:23');
INSERT INTO `promotions` VALUES (66, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:26:12', '2025-02-13 14:26:12');
INSERT INTO `promotions` VALUES (67, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:26:33', '2025-02-13 14:26:33');
INSERT INTO `promotions` VALUES (68, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:26:35', '2025-02-13 14:26:35');
INSERT INTO `promotions` VALUES (69, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:26:59', '2025-02-13 14:26:59');
INSERT INTO `promotions` VALUES (70, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:27:55', '2025-02-13 14:27:55');
INSERT INTO `promotions` VALUES (71, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:28:01', '2025-02-13 14:28:01');
INSERT INTO `promotions` VALUES (72, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:28:03', '2025-02-13 14:28:03');
INSERT INTO `promotions` VALUES (73, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:28:46', '2025-02-13 14:28:46');
INSERT INTO `promotions` VALUES (74, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:28:47', '2025-02-13 14:28:47');
INSERT INTO `promotions` VALUES (75, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:29:20', '2025-02-13 14:29:20');
INSERT INTO `promotions` VALUES (76, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:30:37', '2025-02-13 14:30:37');
INSERT INTO `promotions` VALUES (77, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:30:39', '2025-02-13 14:30:39');
INSERT INTO `promotions` VALUES (78, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:30:42', '2025-02-13 14:30:42');
INSERT INTO `promotions` VALUES (79, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:33:53', '2025-02-13 14:33:53');
INSERT INTO `promotions` VALUES (80, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:34:13', '2025-02-13 14:34:13');
INSERT INTO `promotions` VALUES (81, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:34:15', '2025-02-13 14:34:15');
INSERT INTO `promotions` VALUES (82, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:34:30', '2025-02-13 14:34:30');
INSERT INTO `promotions` VALUES (83, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:35:10', '2025-02-13 14:35:10');
INSERT INTO `promotions` VALUES (84, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:35:11', '2025-02-13 14:35:11');
INSERT INTO `promotions` VALUES (85, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:35:16', '2025-02-13 14:35:16');
INSERT INTO `promotions` VALUES (86, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:36:14', '2025-02-13 14:36:14');
INSERT INTO `promotions` VALUES (87, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:36:17', '2025-02-13 14:36:17');
INSERT INTO `promotions` VALUES (88, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:36:20', '2025-02-13 14:36:20');
INSERT INTO `promotions` VALUES (89, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:36:29', '2025-02-13 14:36:29');
INSERT INTO `promotions` VALUES (90, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:36:37', '2025-02-13 14:36:37');
INSERT INTO `promotions` VALUES (91, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:37:47', '2025-02-13 14:37:47');
INSERT INTO `promotions` VALUES (92, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:38:26', '2025-02-13 14:38:26');
INSERT INTO `promotions` VALUES (93, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:40:59', '2025-02-13 14:40:59');
INSERT INTO `promotions` VALUES (94, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:45:27', '2025-02-13 14:45:27');
INSERT INTO `promotions` VALUES (95, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:45:40', '2025-02-13 14:45:40');
INSERT INTO `promotions` VALUES (96, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:45:41', '2025-02-13 14:45:41');
INSERT INTO `promotions` VALUES (97, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:46:24', '2025-02-13 14:46:24');
INSERT INTO `promotions` VALUES (98, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:46:27', '2025-02-13 14:46:27');
INSERT INTO `promotions` VALUES (99, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:50:36', '2025-02-13 14:50:36');
INSERT INTO `promotions` VALUES (100, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:52:04', '2025-02-13 14:52:04');
INSERT INTO `promotions` VALUES (101, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:53:42', '2025-02-13 14:53:42');
INSERT INTO `promotions` VALUES (102, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:53:46', '2025-02-13 14:53:46');
INSERT INTO `promotions` VALUES (103, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:57:00', '2025-02-13 14:57:00');
INSERT INTO `promotions` VALUES (104, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:58:18', '2025-02-13 14:58:18');
INSERT INTO `promotions` VALUES (105, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:58:25', '2025-02-13 14:58:25');
INSERT INTO `promotions` VALUES (106, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:58:36', '2025-02-13 14:58:36');
INSERT INTO `promotions` VALUES (107, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:59:03', '2025-02-13 14:59:03');
INSERT INTO `promotions` VALUES (108, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:59:04', '2025-02-13 14:59:04');
INSERT INTO `promotions` VALUES (109, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 14:59:12', '2025-02-13 14:59:12');
INSERT INTO `promotions` VALUES (110, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:04:28', '2025-02-13 15:04:28');
INSERT INTO `promotions` VALUES (111, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:06:02', '2025-02-13 15:06:02');
INSERT INTO `promotions` VALUES (112, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:08:42', '2025-02-13 15:08:42');
INSERT INTO `promotions` VALUES (113, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:12:43', '2025-02-13 15:12:43');
INSERT INTO `promotions` VALUES (114, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:14:25', '2025-02-13 15:14:25');
INSERT INTO `promotions` VALUES (115, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:14:32', '2025-02-13 15:14:32');
INSERT INTO `promotions` VALUES (116, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:14:43', '2025-02-13 15:14:43');
INSERT INTO `promotions` VALUES (117, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:16:38', '2025-02-13 15:16:38');
INSERT INTO `promotions` VALUES (118, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:16:48', '2025-02-13 15:16:48');
INSERT INTO `promotions` VALUES (119, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:17:11', '2025-02-13 15:17:11');
INSERT INTO `promotions` VALUES (120, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:18:19', '2025-02-13 15:18:19');
INSERT INTO `promotions` VALUES (121, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:20:06', '2025-02-13 15:20:06');
INSERT INTO `promotions` VALUES (122, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:20:11', '2025-02-13 15:20:11');
INSERT INTO `promotions` VALUES (123, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:20:12', '2025-02-13 15:20:12');
INSERT INTO `promotions` VALUES (124, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:20:21', '2025-02-13 15:20:21');
INSERT INTO `promotions` VALUES (125, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:20:22', '2025-02-13 15:20:22');
INSERT INTO `promotions` VALUES (126, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:21:48', '2025-02-13 15:21:48');
INSERT INTO `promotions` VALUES (127, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:22:28', '2025-02-13 15:22:28');
INSERT INTO `promotions` VALUES (128, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:23:38', '2025-02-13 15:23:38');
INSERT INTO `promotions` VALUES (129, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:23:45', '2025-02-13 15:23:45');
INSERT INTO `promotions` VALUES (130, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:25:06', '2025-02-13 15:25:06');
INSERT INTO `promotions` VALUES (131, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:27:05', '2025-02-13 15:27:05');
INSERT INTO `promotions` VALUES (132, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:28:33', '2025-02-13 15:28:33');
INSERT INTO `promotions` VALUES (133, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:28:48', '2025-02-13 15:28:48');
INSERT INTO `promotions` VALUES (134, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:31:58', '2025-02-13 15:31:58');
INSERT INTO `promotions` VALUES (135, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:32:08', '2025-02-13 15:32:08');
INSERT INTO `promotions` VALUES (136, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:37:11', '2025-02-13 15:37:11');
INSERT INTO `promotions` VALUES (137, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:37:19', '2025-02-13 15:37:19');
INSERT INTO `promotions` VALUES (138, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:37:29', '2025-02-13 15:37:29');
INSERT INTO `promotions` VALUES (139, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:38:47', '2025-02-13 15:38:47');
INSERT INTO `promotions` VALUES (140, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:38:52', '2025-02-13 15:38:52');
INSERT INTO `promotions` VALUES (141, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:39:03', '2025-02-13 15:39:03');
INSERT INTO `promotions` VALUES (142, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:39:33', '2025-02-13 15:39:33');
INSERT INTO `promotions` VALUES (143, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:39:36', '2025-02-13 15:39:36');
INSERT INTO `promotions` VALUES (144, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:40:45', '2025-02-13 15:40:45');
INSERT INTO `promotions` VALUES (145, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:40:48', '2025-02-13 15:40:48');
INSERT INTO `promotions` VALUES (146, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:42:01', '2025-02-13 15:42:01');
INSERT INTO `promotions` VALUES (147, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:43:34', '2025-02-13 15:43:34');
INSERT INTO `promotions` VALUES (148, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:43:50', '2025-02-13 15:43:50');
INSERT INTO `promotions` VALUES (149, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:43:52', '2025-02-13 15:43:52');
INSERT INTO `promotions` VALUES (150, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:44:04', '2025-02-13 15:44:04');
INSERT INTO `promotions` VALUES (151, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:44:05', '2025-02-13 15:44:05');
INSERT INTO `promotions` VALUES (152, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:46:23', '2025-02-13 15:46:23');
INSERT INTO `promotions` VALUES (153, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:46:59', '2025-02-13 15:46:59');
INSERT INTO `promotions` VALUES (154, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:47:00', '2025-02-13 15:47:00');
INSERT INTO `promotions` VALUES (155, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:48:38', '2025-02-13 15:48:38');
INSERT INTO `promotions` VALUES (156, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:48:51', '2025-02-13 15:48:51');
INSERT INTO `promotions` VALUES (157, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:48:52', '2025-02-13 15:48:52');
INSERT INTO `promotions` VALUES (158, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:49:21', '2025-02-13 15:49:21');
INSERT INTO `promotions` VALUES (159, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:49:23', '2025-02-13 15:49:23');
INSERT INTO `promotions` VALUES (160, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:49:40', '2025-02-13 15:49:40');
INSERT INTO `promotions` VALUES (161, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:49:40', '2025-02-13 15:49:40');
INSERT INTO `promotions` VALUES (162, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:50:44', '2025-02-13 15:50:44');
INSERT INTO `promotions` VALUES (163, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:51:37', '2025-02-13 15:51:37');
INSERT INTO `promotions` VALUES (164, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:51:37', '2025-02-13 15:51:37');
INSERT INTO `promotions` VALUES (165, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:51:53', '2025-02-13 15:51:53');
INSERT INTO `promotions` VALUES (166, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:51:54', '2025-02-13 15:51:54');
INSERT INTO `promotions` VALUES (167, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:55:09', '2025-02-13 15:55:09');
INSERT INTO `promotions` VALUES (168, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:55:18', '2025-02-13 15:55:18');
INSERT INTO `promotions` VALUES (169, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:57:20', '2025-02-13 15:57:20');
INSERT INTO `promotions` VALUES (170, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:57:24', '2025-02-13 15:57:24');
INSERT INTO `promotions` VALUES (171, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:57:25', '2025-02-13 15:57:25');
INSERT INTO `promotions` VALUES (172, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:58:01', '2025-02-13 15:58:01');
INSERT INTO `promotions` VALUES (173, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 15:59:21', '2025-02-13 15:59:21');
INSERT INTO `promotions` VALUES (174, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 16:00:41', '2025-02-13 16:00:41');
INSERT INTO `promotions` VALUES (175, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 16:02:02', '2025-02-13 16:02:02');
INSERT INTO `promotions` VALUES (176, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 16:02:24', '2025-02-13 16:02:24');
INSERT INTO `promotions` VALUES (177, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 16:05:11', '2025-02-13 16:05:11');
INSERT INTO `promotions` VALUES (178, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 16:05:12', '2025-02-13 16:05:12');
INSERT INTO `promotions` VALUES (179, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 16:05:33', '2025-02-13 16:05:33');
INSERT INTO `promotions` VALUES (180, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 16:47:25', '2025-02-13 16:47:25');
INSERT INTO `promotions` VALUES (181, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 17:36:50', '2025-02-13 17:36:50');
INSERT INTO `promotions` VALUES (182, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 18:00:33', '2025-02-13 18:00:33');
INSERT INTO `promotions` VALUES (183, 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', NULL, NULL, NULL, 1, 1, '2025-02-13 18:39:28', '2025-02-13 18:39:28');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'user',
  `points` int NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Admin', 'admin@sripongpark.com', '$2y$10$mpkMUn5htHdOxVFyywMfru06iS.NImnXVlIBpKMdDL40o1qJxwWgu', '0826446466', 'admin', 0, '2025-02-13 12:02:15', '2025-02-13 12:02:15');

SET FOREIGN_KEY_CHECKS = 1;
