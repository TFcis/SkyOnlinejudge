-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: 2015 年 02 月 17 日 10:19
-- 伺服器版本: 5.5.38-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 資料庫: `toj`
--

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_account`
--

CREATE TABLE IF NOT EXISTS `tojtest_account` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `passhash` varchar(200) COLLATE utf8_bin NOT NULL,
  `nickname` varchar(64) COLLATE utf8_bin NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `nickname_2` (`nickname`),
  UNIQUE KEY `nickname_3` (`nickname`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_cache`
--

CREATE TABLE IF NOT EXISTS `tojtest_cache` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `timeout` int(11) NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_codepad`
--

CREATE TABLE IF NOT EXISTS `tojtest_codepad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `hash` char(30) COLLATE utf8_bin NOT NULL,
  `filename` char(64) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_ojlist`
--

CREATE TABLE IF NOT EXISTS `tojtest_ojlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` char(64) COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `available` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class` (`class`),
  UNIQUE KEY `class_2` (`class`),
  UNIQUE KEY `class_3` (`class`),
  UNIQUE KEY `class_4` (`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_plugin`
--

CREATE TABLE IF NOT EXISTS `tojtest_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` char(64) COLLATE utf8_bin NOT NULL,
  `version` text COLLATE utf8_bin NOT NULL,
  `author` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class` (`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_profile`
--

CREATE TABLE IF NOT EXISTS `tojtest_profile` (
  `uid` int(11) NOT NULL,
  `quote` text COLLATE utf8_bin,
  `quote_ref` text COLLATE utf8_bin,
  `avatarurl` text COLLATE utf8_bin,
  `backgroundurl` text COLLATE utf8_bin,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_skysystem`
--

CREATE TABLE IF NOT EXISTS `tojtest_skysystem` (
  `name` char(64) COLLATE utf8_bin NOT NULL,
  `var` text COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_statsboard`
--

CREATE TABLE IF NOT EXISTS `tojtest_statsboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `owner` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userlist` text COLLATE utf8_bin NOT NULL,
  `problems` text COLLATE utf8_bin NOT NULL,
  `announce` text COLLATE utf8_bin,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_syslog`
--

CREATE TABLE IF NOT EXISTS `tojtest_syslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `namespace` char(64) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_userojacct`
--

CREATE TABLE IF NOT EXISTS `tojtest_userojacct` (
  `indexid` char(40) COLLATE utf8_bin NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `account` char(64) COLLATE utf8_bin NOT NULL,
  `approve` int(11) NOT NULL,
  PRIMARY KEY (`indexid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_usertoken`
--

CREATE TABLE IF NOT EXISTS `tojtest_usertoken` (
  `uid` int(11) NOT NULL,
  `timeout` int(11) NOT NULL,
  `type` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
