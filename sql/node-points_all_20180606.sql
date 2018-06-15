/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost:3306
 Source Schema         : node-points

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : 65001

 Date: 15/06/2018 10:52:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ar_internal_metadata
-- ----------------------------
DROP TABLE IF EXISTS `ar_internal_metadata`;
CREATE TABLE `ar_internal_metadata` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for course_sign_failures
-- ----------------------------
DROP TABLE IF EXISTS `course_sign_failures`;
CREATE TABLE `course_sign_failures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) DEFAULT NULL,
  `student_id` bigint(20) DEFAULT NULL,
  `message` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_course_sign_failures_on_course_id` (`course_id`),
  KEY `index_course_sign_failures_on_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for course_signs
-- ----------------------------
DROP TABLE IF EXISTS `course_signs`;
CREATE TABLE `course_signs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) DEFAULT NULL,
  `student_id` bigint(20) DEFAULT NULL,
  `received_nodes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_sent` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_course_signs_on_course_id` (`course_id`),
  KEY `index_course_signs_on_student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=651 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for courses
-- ----------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `teather` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for extra_nodes
-- ----------------------------
DROP TABLE IF EXISTS `extra_nodes`;
CREATE TABLE `extra_nodes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) DEFAULT NULL,
  `description` text,
  `nodes` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `course_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_STUDENTS_EXTRAS` (`student_id`),
  KEY `FK_COURSES_EXTRAS` (`course_id`),
  CONSTRAINT `FK_COURSES_EXTRAS` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `FK_STUDENTS_EXTRAS` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for feedbacks
-- ----------------------------
DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) DEFAULT NULL,
  `content` text,
  `nodes` int(11) DEFAULT NULL,
  `stars` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_feedbacks_on_student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for giveaways
-- ----------------------------
DROP TABLE IF EXISTS `giveaways`;
CREATE TABLE `giveaways` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) DEFAULT NULL,
  `student_id` bigint(20) DEFAULT NULL,
  `nodes` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_sent` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_giveaways_on_course_id` (`course_id`),
  KEY `index_giveaways_on_student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for node_rank
-- ----------------------------
DROP TABLE IF EXISTS `node_rank`;
CREATE TABLE `node_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `total` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  KEY `total` (`total`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for schema_migrations
-- ----------------------------
DROP TABLE IF EXISTS `schema_migrations`;
CREATE TABLE `schema_migrations` (
  `version` varchar(255) NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `wallet_address` varchar(255) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '微信用户id',
  `nickname` varchar(255) DEFAULT NULL COMMENT '微信用户昵称',
  `profile` varchar(255) DEFAULT NULL COMMENT '头像URL',
  `comments` varchar(255) DEFAULT NULL COMMENT '备注，可以存放其他信息',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid_key` (`openid`(10)) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
