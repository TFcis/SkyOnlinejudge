-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2016-02-16 10:29:14
-- 伺服器版本: 10.1.10-MariaDB
-- PHP 版本： 7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `sky`
--

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_account`
--

DROP TABLE IF EXISTS `tojtest_account`;
CREATE TABLE IF NOT EXISTS `tojtest_account` (
  `uid` int(11) NOT NULL,
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `passhash` varchar(200) COLLATE utf8_bin NOT NULL,
  `nickname` varchar(64) COLLATE utf8_bin NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_cache`
--

DROP TABLE IF EXISTS `tojtest_cache`;
CREATE TABLE IF NOT EXISTS `tojtest_cache` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `timeout` int(11) NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_codepad`
--

DROP TABLE IF EXISTS `tojtest_codepad`;
CREATE TABLE IF NOT EXISTS `tojtest_codepad` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `hash` char(30) COLLATE utf8_bin NOT NULL,
  `filename` char(64) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_ojlist`
--

DROP TABLE IF EXISTS `tojtest_ojlist`;
CREATE TABLE IF NOT EXISTS `tojtest_ojlist` (
  `id` int(11) NOT NULL,
  `class` char(64) COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `available` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_plugin`
--

DROP TABLE IF EXISTS `tojtest_plugin`;
CREATE TABLE IF NOT EXISTS `tojtest_plugin` (
  `id` int(11) NOT NULL,
  `class` char(64) COLLATE utf8_bin NOT NULL,
  `version` text COLLATE utf8_bin NOT NULL,
  `author` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_profile`
--

DROP TABLE IF EXISTS `tojtest_profile`;
CREATE TABLE IF NOT EXISTS `tojtest_profile` (
  `uid` int(11) NOT NULL,
  `quote` text COLLATE utf8_bin,
  `quote_ref` text COLLATE utf8_bin,
  `avatarurl` text COLLATE utf8_bin,
  `backgroundurl` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_skysystem`
--

DROP TABLE IF EXISTS `tojtest_skysystem`;
CREATE TABLE IF NOT EXISTS `tojtest_skysystem` (
  `name` char(64) COLLATE utf8_bin NOT NULL,
  `var` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_statsboard`
--

DROP TABLE IF EXISTS `tojtest_statsboard`;
CREATE TABLE IF NOT EXISTS `tojtest_statsboard` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `owner` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userlist` text COLLATE utf8_bin NOT NULL,
  `problems` text COLLATE utf8_bin NOT NULL,
  `announce` text COLLATE utf8_bin,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_syslog`
--

DROP TABLE IF EXISTS `tojtest_syslog`;
CREATE TABLE IF NOT EXISTS `tojtest_syslog` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `level` char(64) COLLATE utf8_bin DEFAULT NULL,
  `message` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_userojacct`
--

DROP TABLE IF EXISTS `tojtest_userojacct`;
CREATE TABLE IF NOT EXISTS `tojtest_userojacct` (
  `indexid` char(40) COLLATE utf8_bin NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `account` char(64) COLLATE utf8_bin NOT NULL,
  `approve` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_usertoken`
--

DROP TABLE IF EXISTS `tojtest_usertoken`;
CREATE TABLE IF NOT EXISTS `tojtest_usertoken` (
  `uid` int(11) NOT NULL,
  `timeout` int(11) NOT NULL,
  `type` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `tojtest_account`
--
ALTER TABLE `tojtest_account`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD UNIQUE KEY `nickname_2` (`nickname`),
  ADD UNIQUE KEY `nickname_3` (`nickname`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `uid` (`uid`);

--
-- 資料表索引 `tojtest_cache`
--
ALTER TABLE `tojtest_cache`
  ADD PRIMARY KEY (`name`);

--
-- 資料表索引 `tojtest_codepad`
--
ALTER TABLE `tojtest_codepad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `filename` (`filename`),
  ADD UNIQUE KEY `hash` (`hash`);

--
-- 資料表索引 `tojtest_ojlist`
--
ALTER TABLE `tojtest_ojlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class` (`class`),
  ADD UNIQUE KEY `class_2` (`class`),
  ADD UNIQUE KEY `class_3` (`class`),
  ADD UNIQUE KEY `class_4` (`class`);

--
-- 資料表索引 `tojtest_plugin`
--
ALTER TABLE `tojtest_plugin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class` (`class`);

--
-- 資料表索引 `tojtest_profile`
--
ALTER TABLE `tojtest_profile`
  ADD PRIMARY KEY (`uid`);

--
-- 資料表索引 `tojtest_skysystem`
--
ALTER TABLE `tojtest_skysystem`
  ADD UNIQUE KEY `name` (`name`);

--
-- 資料表索引 `tojtest_statsboard`
--
ALTER TABLE `tojtest_statsboard`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- 資料表索引 `tojtest_syslog`
--
ALTER TABLE `tojtest_syslog`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `tojtest_userojacct`
--
ALTER TABLE `tojtest_userojacct`
  ADD PRIMARY KEY (`indexid`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `tojtest_account`
--
ALTER TABLE `tojtest_account`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_codepad`
--
ALTER TABLE `tojtest_codepad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_ojlist`
--
ALTER TABLE `tojtest_ojlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_plugin`
--
ALTER TABLE `tojtest_plugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_statsboard`
--
ALTER TABLE `tojtest_statsboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_syslog`
--
ALTER TABLE `tojtest_syslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
