-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2017 at 03:36 PM
-- Server version: 5.7.17-0ubuntu0.16.04.2
-- PHP Version: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laslo`
--
CREATE DATABASE IF NOT EXISTS `laslo` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `laslo`;

-- --------------------------------------------------------

--
-- Table structure for table `sysApplicationAclList`
--

CREATE TABLE `sysApplicationAclList` (
  `saal_indexId` int(11) UNSIGNED NOT NULL,
  `saalAclName` varchar(50) NOT NULL,
  `saalAclDescription` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Acl''s avalible to an applicaion.';

-- --------------------------------------------------------

--
-- Table structure for table `sysApplicationList`
--

CREATE TABLE `sysApplicationList` (
  `sal_IndexId` int(11) UNSIGNED NOT NULL,
  `sal_Enabled` int(1) NOT NULL,
  `sal_NameId` varchar(255) NOT NULL,
  `sal_Name` varchar(50) NOT NULL DEFAULT '0',
  `sal_Description` varchar(50) NOT NULL DEFAULT '0',
  `sal_Order` int(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of all the applications ';

--
-- Dumping data for table `sysApplicationList`
--

INSERT INTO `sysApplicationList` (`sal_IndexId`, `sal_Enabled`, `sal_NameId`, `sal_Name`, `sal_Description`, `sal_Order`) VALUES
(1, 1, 'app1', 'app1', 'Application 1', 21),
(2, 1, 'app2', 'app2', 'Application 2', 22),
(3, 1, 'app3', 'app3', 'Application 3', 5);

-- --------------------------------------------------------

--
-- Table structure for table `sysGroupApplicaionAclList`
--

CREATE TABLE `sysGroupApplicaionAclList` (
  `sgaalIndexId` int(11) NOT NULL,
  `sgaal_suglIndexId` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `sgaal_salIndexId` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `sgaal_saalIndexId` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `sgaalAclGuest` int(11) NOT NULL DEFAULT '0',
  `sgaalAclUser` int(11) NOT NULL DEFAULT '0',
  `sgaalAclAdmin` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Permissions for applicaions per group.';

-- --------------------------------------------------------

--
-- Table structure for table `sysGroupApplicationList`
--

CREATE TABLE `sysGroupApplicationList` (
  `sgal_sglIndexId` int(11) UNSIGNED NOT NULL,
  `sgal_salNameId` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of applications a group has access to.';

--
-- Dumping data for table `sysGroupApplicationList`
--

INSERT INTO `sysGroupApplicationList` (`sgal_sglIndexId`, `sgal_salNameId`) VALUES
(1, 'app1'),
(1, 'app2'),
(1, 'app3'),
(2, 'app1'),
(2, 'app2'),
(3, 'app2'),
(3, 'app3');

-- --------------------------------------------------------

--
-- Table structure for table `sysGroupList`
--

CREATE TABLE `sysGroupList` (
  `sglIndexId` int(11) UNSIGNED NOT NULL,
  `sglIndexName` varchar(50) NOT NULL DEFAULT '0',
  `sglName` varchar(50) NOT NULL DEFAULT '0',
  `sglDescription` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of user groups.';

--
-- Dumping data for table `sysGroupList`
--

INSERT INTO `sysGroupList` (`sglIndexId`, `sglIndexName`, `sglName`, `sglDescription`) VALUES
(1, 'test1', 'Test 1', 'app1,2,3'),
(2, 'test2', 'Test 2', 'app1,2'),
(3, 'test3', 'Test 3', 'App2,3');

-- --------------------------------------------------------

--
-- Table structure for table `sysSettingsTable`
--

CREATE TABLE `sysSettingsTable` (
  `sst_Index` int(11) NOT NULL,
  `sst_TableName` varchar(255) NOT NULL DEFAULT '0',
  `sst_SettingName` varchar(255) NOT NULL DEFAULT '0',
  `sst_SettingType` enum('string','int','YN') NOT NULL DEFAULT 'string',
  `sst_Setting Value` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=ascii COMMENT='Table of tables';

--
-- Dumping data for table `sysSettingsTable`
--

INSERT INTO `sysSettingsTable` (`sst_Index`, `sst_TableName`, `sst_SettingName`, `sst_SettingType`, `sst_Setting Value`) VALUES
(2, 'siteConfig', 'siteTitle', 'string', 'Laslo'),
(3, 'siteConfig', 'logoLocation', 'string', 'logo.png'),
(4, 'siteConfig', 'logoTitle', 'string', 'Laslo'),
(5, 'siteConfig', 'LogoUrlLink', 'string', ''),
(6, 'siteConfig', 'cookiePath', 'string', ''),
(7, 'siteConfig', 'cookieDomain', 'string', ''),
(8, 'siteConfig', 'sessionTimeoutSeconds', 'int', '28800'),
(9, 'siteConfig', 'cookieRequired', 'string', 'Y'),
(10, 'siteConfig', 'loginAttempsFailBlockAccount', 'int', '3'),
(11, 'siteConfig', 'loginAttempsFailBlockIp', 'int', '9'),
(12, 'siteConfig', 'LoginFailBlockAccountInMinuites', 'int', '15'),
(13, 'siteConfig', 'siteTimeZone', 'string', ''),
(14, 'siteConfig', 'siteCountry', 'string', ''),
(15, 'siteConfig', 'siteLanguage', 'string', ''),
(16, 'siteConfig', 'logDaysRecordsStay', 'int', '90');

-- --------------------------------------------------------

--
-- Table structure for table `sysUserGroupList`
--

CREATE TABLE `sysUserGroupList` (
  `suglIndexId` int(11) UNSIGNED NOT NULL,
  `sugl_sulIndexId` int(11) UNSIGNED NOT NULL,
  `sugl_sglIndexid` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of Groups that belong to a user.';

--
-- Dumping data for table `sysUserGroupList`
--

INSERT INTO `sysUserGroupList` (`suglIndexId`, `sugl_sulIndexId`, `sugl_sglIndexid`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 2),
(5, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sysUserList`
--

CREATE TABLE `sysUserList` (
  `sulIndexId` int(11) UNSIGNED NOT NULL,
  `sulAccountStatus` tinyint(1) NOT NULL,
  `sulUserId` varchar(50) NOT NULL DEFAULT '0',
  `sulPasswordId` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `sulLastLogin` date NOT NULL,
  `sulLastLoginFrom` varchar(50) NOT NULL DEFAULT '0',
  `sulLastPasswordChange` date NOT NULL,
  `sulAccountType` char(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of users in the system.';

--
-- Dumping data for table `sysUserList`
--

INSERT INTO `sysUserList` (`sulIndexId`, `sulAccountStatus`, `sulUserId`, `sulPasswordId`, `sulLastLogin`, `sulLastLoginFrom`, `sulLastPasswordChange`, `sulAccountType`) VALUES
(1, 0, 'user1', 0, '0000-00-00', '0', '0000-00-00', '0'),
(2, 0, 'user2', 0, '0000-00-00', '0', '0000-00-00', '0'),
(3, 0, 'user3', 0, '0000-00-00', '0', '0000-00-00', '0'),
(4, 0, 'user4', 0, '0000-00-00', '0', '0000-00-00', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sysApplicationAclList`
--
ALTER TABLE `sysApplicationAclList`
  ADD PRIMARY KEY (`saal_indexId`);

--
-- Indexes for table `sysApplicationList`
--
ALTER TABLE `sysApplicationList`
  ADD PRIMARY KEY (`sal_IndexId`),
  ADD UNIQUE KEY `sal_NameId_U` (`sal_NameId`);

--
-- Indexes for table `sysGroupApplicaionAclList`
--
ALTER TABLE `sysGroupApplicaionAclList`
  ADD PRIMARY KEY (`sgaalIndexId`),
  ADD KEY `FK_sysGroupApplicaionAclList_sysUserGroupList` (`sgaal_suglIndexId`),
  ADD KEY `FK_sysGroupApplicaionAclList_sysApplicationList` (`sgaal_salIndexId`),
  ADD KEY `FK_sysGroupApplicaionAclList_sysApplicationAclList` (`sgaal_saalIndexId`);

--
-- Indexes for table `sysGroupApplicationList`
--
ALTER TABLE `sysGroupApplicationList`
  ADD UNIQUE KEY `sgal_sglIndexId_sgal_salNameId` (`sgal_sglIndexId`,`sgal_salNameId`),
  ADD KEY `FK__sysGroupList` (`sgal_sglIndexId`),
  ADD KEY `FK__sysApplicationList` (`sgal_salNameId`);

--
-- Indexes for table `sysGroupList`
--
ALTER TABLE `sysGroupList`
  ADD PRIMARY KEY (`sglIndexId`);

--
-- Indexes for table `sysSettingsTable`
--
ALTER TABLE `sysSettingsTable`
  ADD PRIMARY KEY (`sst_Index`),
  ADD UNIQUE KEY `sst_TableSettingName` (`sst_TableName`,`sst_SettingName`);

--
-- Indexes for table `sysUserGroupList`
--
ALTER TABLE `sysUserGroupList`
  ADD PRIMARY KEY (`suglIndexId`),
  ADD KEY `FK_sysUserGroupList_sysUserList` (`sugl_sulIndexId`),
  ADD KEY `FK_sysUserGroupList_sysGroupList` (`sugl_sglIndexid`);

--
-- Indexes for table `sysUserList`
--
ALTER TABLE `sysUserList`
  ADD PRIMARY KEY (`sulIndexId`),
  ADD UNIQUE KEY `sulUserId` (`sulUserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sysApplicationAclList`
--
ALTER TABLE `sysApplicationAclList`
  MODIFY `saal_indexId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sysApplicationList`
--
ALTER TABLE `sysApplicationList`
  MODIFY `sal_IndexId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sysGroupApplicaionAclList`
--
ALTER TABLE `sysGroupApplicaionAclList`
  MODIFY `sgaalIndexId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sysGroupList`
--
ALTER TABLE `sysGroupList`
  MODIFY `sglIndexId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sysSettingsTable`
--
ALTER TABLE `sysSettingsTable`
  MODIFY `sst_Index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `sysUserGroupList`
--
ALTER TABLE `sysUserGroupList`
  MODIFY `suglIndexId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sysUserList`
--
ALTER TABLE `sysUserList`
  MODIFY `sulIndexId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `sysGroupApplicaionAclList`
--
ALTER TABLE `sysGroupApplicaionAclList`
  ADD CONSTRAINT `FK_sysGroupApplicaionAclList_sysApplicationAclList` FOREIGN KEY (`sgaal_saalIndexId`) REFERENCES `sysApplicationAclList` (`saal_indexId`),
  ADD CONSTRAINT `FK_sysGroupApplicaionAclList_sysUserGroupList` FOREIGN KEY (`sgaal_suglIndexId`) REFERENCES `sysUserGroupList` (`suglIndexId`);

--
-- Constraints for table `sysGroupApplicationList`
--
ALTER TABLE `sysGroupApplicationList`
  ADD CONSTRAINT `FK__sysApplicationList` FOREIGN KEY (`sgal_salNameId`) REFERENCES `sysApplicationList` (`sal_NameId`),
  ADD CONSTRAINT `FK__sysGroupList` FOREIGN KEY (`sgal_sglIndexId`) REFERENCES `sysGroupList` (`sglIndexId`);

--
-- Constraints for table `sysUserGroupList`
--
ALTER TABLE `sysUserGroupList`
  ADD CONSTRAINT `FK_sysUserGroupList_sysGroupList` FOREIGN KEY (`sugl_sglIndexid`) REFERENCES `sysGroupList` (`sglIndexId`),
  ADD CONSTRAINT `FK_sysUserGroupList_sysUserList` FOREIGN KEY (`sugl_sulIndexId`) REFERENCES `sysUserList` (`sulIndexId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
