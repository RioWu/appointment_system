-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2019-05-05 11:10:55
-- 服务器版本： 8.0.13
-- PHP 版本： 7.1.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `appointment_system`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `user_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `user_name`, `password`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70\r\n');

-- --------------------------------------------------------

--
-- 表的结构 `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `images` json NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `available_time` json NOT NULL,
  `place` varchar(500) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `person_order`
--

CREATE TABLE `person_order` (
  `order_id` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `person_id` int(11) NOT NULL,
  `id` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `open_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  `school` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` date NOT NULL,
  `order_period` json NOT NULL,
  `time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `remarks` varchar(500) COLLATE urf8mb4_general_ci NOT NULL,
  `personnum` varchar(500) COLLATE urf8mb4_general_ci NOT NULL,
  `place` varchar(500) COLLATE urf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `room_order`
--

CREATE TABLE `room_order` (
  `order_id` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `open_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  `school` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `room_usage` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` date NOT NULL,
  `order_period` json NOT NULL COMMENT '0表示中午,1～12表示从第一节课到第十二节课',
  `status` tinyint(1) NOT NULL COMMENT '0表示待审核，1表示审核通过',
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `salon`
--

CREATE TABLE `salon` (
  `id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `location` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `speaker` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `images` json NOT NULL,
  `capacity` smallint(8) NOT NULL,
  `count` smallint(8) NOT NULL DEFAULT '0',
  `salon_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `speaker_description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `speaker`
--

CREATE TABLE `speaker` (
  `id` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `team_id` smallint(11) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `teacher_sign_up`
--

CREATE TABLE `teacher_sign_up` (
  `sign_up_id` int(11) NOT NULL,
  `id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `open_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  `teacher_name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` datetime NOT NULL,
  `salon_id` smallint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `team`
--

CREATE TABLE `team` (
  `id` smallint(11) NOT NULL,
  `name` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `images` json NOT NULL,
  `available_time` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `team_order`
--

CREATE TABLE `team_order` (
  `order_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `open_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  `team_id` smallint(11) NOT NULL,
  `school` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` date NOT NULL,
  `order_period` json NOT NULL COMMENT '0表示中午,1～12表示从第一节课到第十二节课',
  `time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT;

--
-- 转储表的索引
--

--
-- 表的索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `person_order`
--
ALTER TABLE `person_order`
  ADD PRIMARY KEY (`order_id`);

--
-- 表的索引 `room_order`
--
ALTER TABLE `room_order`
  ADD PRIMARY KEY (`order_id`);

--
-- 表的索引 `salon`
--
ALTER TABLE `salon`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `speaker`
--
ALTER TABLE `speaker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- 表的索引 `teacher_sign_up`
--
ALTER TABLE `teacher_sign_up`
  ADD PRIMARY KEY (`sign_up_id`);

--
-- 表的索引 `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `team_order`
--
ALTER TABLE `team_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `team_id` (`team_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `person_order`
--
ALTER TABLE `person_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `room_order`
--
ALTER TABLE `room_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `salon`
--
ALTER TABLE `salon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `speaker`
--
ALTER TABLE `speaker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `teacher_sign_up`
--
ALTER TABLE `teacher_sign_up`
  MODIFY `sign_up_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `team`
--
ALTER TABLE `team`
  MODIFY `id` smallint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `team_order`
--
ALTER TABLE `team_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 限制导出的表
--

--
-- 限制表 `speaker`
--
ALTER TABLE `speaker`
  ADD CONSTRAINT `speaker_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- 限制表 `team_order`
--
ALTER TABLE `team_order`
  ADD CONSTRAINT `team_order_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
