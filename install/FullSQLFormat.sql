-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- 主機: localhost
-- 產生時間： 2016-08-05 21:44:46
-- 伺服器版本: 10.1.10-MariaDB
-- PHP 版本： 7.0.4

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

CREATE TABLE `tojtest_account` (
  `uid` int(11) NOT NULL,
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `passhash` varchar(200) COLLATE utf8_bin NOT NULL,
  `nickname` varchar(64) COLLATE utf8_bin NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `realname` varchar(30) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_cache`
--

CREATE TABLE `tojtest_cache` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `timeout` int(11) NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_challenge`
--

CREATE TABLE `tojtest_challenge` (
  `cid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `result` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_codepad`
--

CREATE TABLE `tojtest_codepad` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `hash` char(30) COLLATE utf8_bin NOT NULL,
  `type` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_ojlist`
--

CREATE TABLE `tojtest_ojlist` (
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

CREATE TABLE `tojtest_plugin` (
  `id` int(11) NOT NULL,
  `class` char(64) COLLATE utf8_bin NOT NULL,
  `version` text COLLATE utf8_bin NOT NULL,
  `author` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_problem`
--

CREATE TABLE `tojtest_problem` (
  `pid` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `title` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_profile`
--

CREATE TABLE `tojtest_profile` (
  `uid` int(11) NOT NULL,
  `quote` text COLLATE utf8_bin,
  `quote_ref` text COLLATE utf8_bin,
  `avatarurl` text COLLATE utf8_bin,
  `backgroundurl` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_statsboard`
--

CREATE TABLE `tojtest_statsboard` (
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

CREATE TABLE `tojtest_syslog` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `level` char(64) COLLATE utf8_bin DEFAULT NULL,
  `message` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_sysvalue`
--

CREATE TABLE `tojtest_sysvalue` (
  `name` char(64) COLLATE utf8_bin NOT NULL,
  `var` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `tojtest_userojacct`
--

CREATE TABLE `tojtest_userojacct` (
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

CREATE TABLE `tojtest_usertoken` (
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
  ADD KEY `uid` (`uid`),
  ADD KEY `uid_2` (`uid`);

--
-- 資料表索引 `tojtest_cache`
--
ALTER TABLE `tojtest_cache`
  ADD PRIMARY KEY (`name`);

--
-- 資料表索引 `tojtest_challenge`
--
ALTER TABLE `tojtest_challenge`
  ADD PRIMARY KEY (`cid`);

--
-- 資料表索引 `tojtest_codepad`
--
ALTER TABLE `tojtest_codepad`
  ADD PRIMARY KEY (`id`),
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
-- 資料表索引 `tojtest_problem`
--
ALTER TABLE `tojtest_problem`
  ADD PRIMARY KEY (`pid`);

--
-- 資料表索引 `tojtest_profile`
--
ALTER TABLE `tojtest_profile`
  ADD PRIMARY KEY (`uid`);

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
-- 資料表索引 `tojtest_sysvalue`
--
ALTER TABLE `tojtest_sysvalue`
  ADD UNIQUE KEY `name` (`name`);

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
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_challenge`
--
ALTER TABLE `tojtest_challenge`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_codepad`
--
ALTER TABLE `tojtest_codepad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_ojlist`
--
ALTER TABLE `tojtest_ojlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_plugin`
--
ALTER TABLE `tojtest_plugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_problem`
--
ALTER TABLE `tojtest_problem`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_statsboard`
--
ALTER TABLE `tojtest_statsboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `tojtest_syslog`
--
ALTER TABLE `tojtest_syslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
