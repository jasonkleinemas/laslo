-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2024 at 03:56 PM
-- Server version: 8.0.36-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.15

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laslo_dev`
--
CREATE DATABASE IF NOT EXISTS `laslo_dev` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `laslo_dev`;

-- --------------------------------------------------------

--
-- Table structure for table `sysApplicationDetails`
--

CREATE TABLE `sysApplicationDetails` (
  `sad_IndexId` int UNSIGNED NOT NULL,
  `sad_Status` enum('A','I') NOT NULL,
  `sad_NameId` varchar(255) NOT NULL,
  `sad_ShortID` varchar(255) NOT NULL DEFAULT 'd',
  `sad_Name` varchar(255) NOT NULL DEFAULT '0',
  `sad_Description` varchar(255) NOT NULL DEFAULT '0',
  `sad_Order` int UNSIGNED NOT NULL DEFAULT '0',
  `sad_Version` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of all the applications ';

--
-- RELATIONSHIPS FOR TABLE `sysApplicationDetails`:
--

--
-- Dumping data for table `sysApplicationDetails`
--

INSERT INTO `sysApplicationDetails` (`sad_IndexId`, `sad_Status`, `sad_NameId`, `sad_ShortID`, `sad_Name`, `sad_Description`, `sad_Order`, `sad_Version`) VALUES
(6, 'A', 'deveolper_apptest', 'd', 'Dev Test', 'For testing', 21, ''),
(9, 'A', 'base_admin', 'd', 'Admin', 'Administration', 1, ''),
(11, 'I', 'base_api', 'd', 'base_api', 'System level. Do not remove.', 0, '0'),
(12, 'I', 'base_home', 'base_home', 'Home', 'System level. Do not remove.', 0, '0'),
(13, 'A', 'construction', 'construction', 'Construction', 'Application for the construction group.', 12, '0'),
(14, 'A', 'plant', 'd', 'Plant', 'Application for Plant group.', 5, '.1'),
(15, 'A', 'co', 'd', 'CO', 'Application for CO group.', 2, '.1');

-- --------------------------------------------------------

--
-- Table structure for table `sysApplicationJobs`
--

CREATE TABLE `sysApplicationJobs` (
  `saj_UUID` varchar(255) NOT NULL,
  `saj_sad_NameId` varchar(255) NOT NULL,
  `saj_Purpose` varchar(255) NOT NULL,
  `saj_TypeOfCall` enum('P','I') NOT NULL DEFAULT 'I',
  `saj_FileName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Default jobs for applications';

--
-- RELATIONSHIPS FOR TABLE `sysApplicationJobs`:
--   `saj_sad_NameId`
--       `sysApplicationDetails` -> `sad_NameId`
--

--
-- Dumping data for table `sysApplicationJobs`
--

INSERT INTO `sysApplicationJobs` (`saj_UUID`, `saj_sad_NameId`, `saj_Purpose`, `saj_TypeOfCall`, `saj_FileName`) VALUES
('2f51bffc-dfd9-11e9-85c1-7446a0b52568', 'deveolper_apptest', 'Test Cron Call', 'P', 'testpgm.php'),
('983e6c16-f692-11e9-85c1-7446a0b52568', 'base_admin', 'Email System', 'P', 'ejs_ReceveSend.pl'),
('9869ce8c-e0a4-11e9-85c1-7446a0b52568', 'deveolper_apptest', 'Test Cron Include', 'I', 'testpgm.inc.php'),
('98d19924-f501-11e9-85c1-7446a0b52568', 'base_admin', 'Roteate the logs.', 'I', 'sys_RotateLogs.php');

-- --------------------------------------------------------

--
-- Table structure for table `sysApplicationPermissions`
--

CREATE TABLE `sysApplicationPermissions` (
  `sap_IndexId` int UNSIGNED NOT NULL,
  `sap_NameId` varchar(255) NOT NULL,
  `sap_sad_NameId` varchar(255) NOT NULL,
  `sap_Description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Permissions for applications.';

--
-- RELATIONSHIPS FOR TABLE `sysApplicationPermissions`:
--   `sap_sad_NameId`
--       `sysApplicationDetails` -> `sad_NameId`
--

--
-- Dumping data for table `sysApplicationPermissions`
--

INSERT INTO `sysApplicationPermissions` (`sap_IndexId`, `sap_NameId`, `sap_sad_NameId`, `sap_Description`) VALUES
(10, 'sysAppAccess', 'deveolper_apptest', 'Application Permission'),
(11, 'test1PerName2', 'deveolper_apptest', 'test 1 Desc'),
(12, 'test1PerName3', 'deveolper_apptest', 'test 1 Desc'),
(13, 'test1PerName4', 'deveolper_apptest', 'test 1 Desc'),
(14, 'sysAppAccess', 'base_admin', 'Application Permission'),
(20, 'sysAppAccess', 'construction', 'Application Permission'),
(22, 'sysAppAccess', 'plant', 'sysAppAccess'),
(23, 'sysAppAccess', 'co', 'sysAppAccess');

-- --------------------------------------------------------

--
-- Table structure for table `sysCompanyDetails`
--

CREATE TABLE `sysCompanyDetails` (
  `scd_CompanyId` int UNSIGNED NOT NULL,
  `scd_Name` varchar(255) NOT NULL,
  `scd_Description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysCompanyDetails`:
--

--
-- Dumping data for table `sysCompanyDetails`
--

INSERT INTO `sysCompanyDetails` (`scd_CompanyId`, `scd_Name`, `scd_Description`) VALUES
(0, 'Default No Company', 'This should no be used just a place holder. Id should be zero.'),
(1, 'Sample 1', 'Sample 1'),
(2, 'Sample 2', 'Sample 2');

-- --------------------------------------------------------

--
-- Table structure for table `sysDepartmentDetails`
--

CREATE TABLE `sysDepartmentDetails` (
  `sdd_DepartmentId` int UNSIGNED NOT NULL,
  `sdd_scd_CompanyId` int UNSIGNED NOT NULL,
  `sdd_Name` varchar(255) NOT NULL,
  `sdd_Description` varchar(255) NOT NULL,
  `sdd_Location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysDepartmentDetails`:
--   `sdd_scd_CompanyId`
--       `sysCompanyDetails` -> `scd_CompanyId`
--

--
-- Dumping data for table `sysDepartmentDetails`
--

INSERT INTO `sysDepartmentDetails` (`sdd_DepartmentId`, `sdd_scd_CompanyId`, `sdd_Name`, `sdd_Description`, `sdd_Location`) VALUES
(1, 1, 'Dept1', 'Test Dept 1', 'no where'),
(2, 1, 'Dept2', 'Test Dept 2', 'no where 2');

-- --------------------------------------------------------

--
-- Table structure for table `sysDepartmentPreferences`
--

CREATE TABLE `sysDepartmentPreferences` (
  `sdp_IndexId` int UNSIGNED NOT NULL,
  `sdp_sdd_DepartmentId` int UNSIGNED DEFAULT NULL,
  `sdp_sad_NameId` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sdp_sskd_TableName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sdp_sskd_SettingName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sdp_Value` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sdp_AllowOverride` enum('Y','N') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysDepartmentPreferences`:
--   `sdp_sdd_DepartmentId`
--       `sysDepartmentDetails` -> `sdd_DepartmentId`
--   `sdp_sad_NameId`
--       `sysSettingsKeyDefinitions` -> `sskd_sad_NameId`
--   `sdp_sskd_TableName`
--       `sysSettingsKeyDefinitions` -> `sskd_TableName`
--   `sdp_sskd_SettingName`
--       `sysSettingsKeyDefinitions` -> `sskd_SettingName`
--

-- --------------------------------------------------------

--
-- Table structure for table `sysEmailAddresses`
--

CREATE TABLE `sysEmailAddresses` (
  `sea_UUID` varchar(255) NOT NULL,
  `sea_NameId` varchar(255) NOT NULL,
  `sea_Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysEmailAddresses`:
--

--
-- Dumping data for table `sysEmailAddresses`
--

INSERT INTO `sysEmailAddresses` (`sea_UUID`, `sea_NameId`, `sea_Address`) VALUES
('d145c9e2-e9f8-11e9-85c1-7446a0b52568', 'none', 'none@none.net');

-- --------------------------------------------------------

--
-- Table structure for table `sysEmailLists`
--

CREATE TABLE `sysEmailLists` (
  `sel_seld_UUID` varchar(255) NOT NULL,
  `sel_sea_UUID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysEmailLists`:
--   `sel_sea_UUID`
--       `sysEmailAddresses` -> `sea_UUID`
--   `sel_seld_UUID`
--       `sysEmailListsDetails` -> `seld_UUID`
--

--
-- Dumping data for table `sysEmailLists`
--

INSERT INTO `sysEmailLists` (`sel_seld_UUID`, `sel_sea_UUID`) VALUES
('4a9f6784-e9ff-11e9-85c1-7446a0b52568', 'd145c9e2-e9f8-11e9-85c1-7446a0b52568');

-- --------------------------------------------------------

--
-- Table structure for table `sysEmailListsDetails`
--

CREATE TABLE `sysEmailListsDetails` (
  `seld_UUID` varchar(255) NOT NULL,
  `seld_NameId` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysEmailListsDetails`:
--

--
-- Dumping data for table `sysEmailListsDetails`
--

INSERT INTO `sysEmailListsDetails` (`seld_UUID`, `seld_NameId`) VALUES
('b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'Empty List'),
('4a9f6784-e9ff-11e9-85c1-7446a0b52568', 'Test 1');

-- --------------------------------------------------------

--
-- Table structure for table `sysEmailServers`
--

CREATE TABLE `sysEmailServers` (
  `ses_UUID` varchar(255) NOT NULL,
  `ses_NameId` varchar(255) NOT NULL,
  `ses_CheckForEmails` enum('Y','N') NOT NULL DEFAULT 'N',
  `ses_UserLoginId` varchar(255) NOT NULL,
  `ses_UserLoginPass` varchar(255) NOT NULL,
  `ses_FromAddress` varchar(255) NOT NULL,
  `ses_FromName` varchar(255) NOT NULL,
  `ses_IncommingServerName` varchar(255) NOT NULL,
  `ses_IncommingPort` varchar(255) NOT NULL,
  `ses_IncommingSSL` enum('Y','N') NOT NULL DEFAULT 'N',
  `ses_IncommingDebug` varchar(255) NOT NULL DEFAULT 'SMTP::DEBUG_OFF',
  `ses_SendingServerName` varchar(255) NOT NULL,
  `ses_SendingTlsOrSsl` varchar(255) NOT NULL,
  `ses_SendingPortTLS` varchar(255) NOT NULL,
  `ses_SendingPortSSL` varchar(255) NOT NULL,
  `ses_SendingDebug` varchar(255) NOT NULL DEFAULT 'SMTP::DEBUG_OFF'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of eMail servers';

--
-- RELATIONSHIPS FOR TABLE `sysEmailServers`:
--

--
-- Dumping data for table `sysEmailServers`
--

INSERT INTO `sysEmailServers` (`ses_UUID`, `ses_NameId`, `ses_CheckForEmails`, `ses_UserLoginId`, `ses_UserLoginPass`, `ses_FromAddress`, `ses_FromName`, `ses_IncommingServerName`, `ses_IncommingPort`, `ses_IncommingSSL`, `ses_IncommingDebug`, `ses_SendingServerName`, `ses_SendingTlsOrSsl`, `ses_SendingPortTLS`, `ses_SendingPortSSL`, `ses_SendingDebug`) VALUES
('b39f57fc-e6ea-11e9-85c1-7446a0b52568', 'none messaging.event', 'N', 'user', 'pass', 'fromt@none.net', 'messaging event', 'mail.server.net', '110', 'N', 'SMTP::DEBUG_OFF', 'ssmtp.server.net', 'tls', '587', '587', 'SMTP::DEBUG_OFF');

-- --------------------------------------------------------

--
-- Table structure for table `sysJobScheduler`
--

CREATE TABLE `sysJobScheduler` (
  `sjs_UUID` varchar(255) NOT NULL,
  `sjs_Status` enum('A','I') NOT NULL DEFAULT 'A',
  `sjs_NameId` varchar(255) NOT NULL,
  `sjs_Schedule` varchar(255) NOT NULL,
  `sjs_saj_UUID` varchar(255) NOT NULL,
  `sjs_ses_UUID` varchar(255) NOT NULL,
  `sjs_seld_UUID_To` varchar(255) NOT NULL,
  `sjs_seld_UUID_Bcc` varchar(255) NOT NULL,
  `sjs_seld_UUID_Cc` varchar(255) NOT NULL,
  `sjs_LastEditUTC` varchar(255) NOT NULL DEFAULT 'None',
  `sjs_LastStartUTC` varchar(255) NOT NULL DEFAULT 'None',
  `sjs_LastRunSeconds` varchar(255) NOT NULL DEFAULT 'Unknown',
  `sjs_LastRunStatus` varchar(255) NOT NULL DEFAULT 'Unknown',
  `sjs_LastRunMessage` varchar(255) NOT NULL DEFAULT 'None',
  `sjs_LastRunLog` varchar(255) NOT NULL DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Job to be scheduled Detail';

--
-- RELATIONSHIPS FOR TABLE `sysJobScheduler`:
--   `sjs_saj_UUID`
--       `sysApplicationJobs` -> `saj_UUID`
--   `sjs_seld_UUID_To`
--       `sysEmailListsDetails` -> `seld_UUID`
--   `sjs_seld_UUID_Bcc`
--       `sysEmailListsDetails` -> `seld_UUID`
--   `sjs_seld_UUID_Cc`
--       `sysEmailListsDetails` -> `seld_UUID`
--   `sjs_ses_UUID`
--       `sysEmailServers` -> `ses_UUID`
--

--
-- Dumping data for table `sysJobScheduler`
--

INSERT INTO `sysJobScheduler` (`sjs_UUID`, `sjs_Status`, `sjs_NameId`, `sjs_Schedule`, `sjs_saj_UUID`, `sjs_ses_UUID`, `sjs_seld_UUID_To`, `sjs_seld_UUID_Bcc`, `sjs_seld_UUID_Cc`, `sjs_LastEditUTC`, `sjs_LastStartUTC`, `sjs_LastRunSeconds`, `sjs_LastRunStatus`, `sjs_LastRunMessage`, `sjs_LastRunLog`) VALUES
('61c857e2-de3b-11e9-85c1-7446a0b52568', 'I', 'tt', 'ee', '2f51bffc-dfd9-11e9-85c1-7446a0b52568', 'b39f57fc-e6ea-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', '2019-10-24T14:55:46Z-05:00', '0', 'None', '0', '0', 'None'),
('6bdb75d4-f50b-11e9-85c1-7446a0b52568', 'I', 'Rotate Logs', '1 0 * * *', '98d19924-f501-11e9-85c1-7446a0b52568', 'b39f57fc-e6ea-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', '2019-10-24T13:31:52Z-05:00', 'None', 'Unknown', 'Unknown', 'None', 'None'),
('ad5350f6-dede-11e9-85c1-7446a0b52568', 'I', 'test2', '*/10 * * * *', '9869ce8c-e0a4-11e9-85c1-7446a0b52568', 'b39f57fc-e6ea-11e9-85c1-7446a0b52568', '4a9f6784-e9ff-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', '2019-10-03T15:32:28Z-05:00', '2019-10-02T08:31:09Z-05:00', '0.00010800361633301', 'Ok:status:0', 'Ok', 'test1.2019-10-02T08_31_09Z-0500.log'),
('e7ea02be-da56-11e9-85c1-7446a0b52568', 'I', 'Send Receve Mail Service', '*/5 * * * *', '983e6c16-f692-11e9-85c1-7446a0b52568', 'b39f57fc-e6ea-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', 'b0ecf298-f5d7-11e9-85c1-7446a0b52568', '2019-11-26T11:29:56Z-06:00', '2019-10-01T15:40:49Z-05:00', '0.025786161422729', 'Ok:status:0', 'Test Program:816213049', 'test1.2019-10-01T15_40_48Z-0500.log');

-- --------------------------------------------------------

--
-- Table structure for table `sysListLists`
--

CREATE TABLE `sysListLists` (
  `sll_IndexId` int UNSIGNED NOT NULL,
  `sll_sad_ApplicationName` varchar(255) NOT NULL,
  `sll_TableName` varchar(255) NOT NULL,
  `sll_TableKey` varchar(255) NOT NULL,
  `sll_TableKeyValue` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysListLists`:
--   `sll_sad_ApplicationName`
--       `sysApplicationDetails` -> `sad_NameId`
--

--
-- Dumping data for table `sysListLists`
--

INSERT INTO `sysListLists` (`sll_IndexId`, `sll_sad_ApplicationName`, `sll_TableName`, `sll_TableKey`, `sll_TableKeyValue`) VALUES
(1, 'base_admin', 'sysStatus', 'A', 'Active'),
(2, 'base_admin', 'sysStatus', 'I', 'Inactive'),
(5, 'base_admin', 'sysYN', 'Y', 'Yes'),
(6, 'base_admin', 'sysYN', 'N', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `sysPreferencesValues`
--

CREATE TABLE `sysPreferencesValues` (
  `spv_OwnerType` varchar(255) NOT NULL,
  `spv_OwnerId` int UNSIGNED NOT NULL,
  `spv_sskd_NameId` varchar(255) NOT NULL,
  `spv_sskd_TableName` varchar(255) NOT NULL,
  `spv_sskd_SettingName` varchar(255) NOT NULL,
  `spv_Value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysPreferencesValues`:
--   `spv_sskd_NameId`
--       `sysSettingsKeyDefinitions` -> `sskd_sad_NameId`
--   `spv_sskd_TableName`
--       `sysSettingsKeyDefinitions` -> `sskd_TableName`
--   `spv_sskd_SettingName`
--       `sysSettingsKeyDefinitions` -> `sskd_SettingName`
--

-- --------------------------------------------------------

--
-- Table structure for table `sysRoleDetails`
--

CREATE TABLE `sysRoleDetails` (
  `srd_IndexId` int UNSIGNED NOT NULL,
  `srd_RoleId` varchar(255) NOT NULL,
  `srd_Description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Roles For users';

--
-- RELATIONSHIPS FOR TABLE `sysRoleDetails`:
--

--
-- Dumping data for table `sysRoleDetails`
--

INSERT INTO `sysRoleDetails` (`srd_IndexId`, `srd_RoleId`, `srd_Description`) VALUES
(2, 'system_dev', 'System Developer Role'),
(5, 'system_admin', 'System Admin Role'),
(11, 'construction', 'Construction'),
(12, 'Plant', 'Plant group'),
(13, 'co', 'Cental Office');

-- --------------------------------------------------------

--
-- Table structure for table `sysRolePermissions`
--

CREATE TABLE `sysRolePermissions` (
  `srp_IndexId` int UNSIGNED NOT NULL,
  `srp_srd_RoleId` varchar(255) NOT NULL,
  `srp_sad_NameId` varchar(255) NOT NULL,
  `srp_sap_NameId` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Permissions for roles.';

--
-- RELATIONSHIPS FOR TABLE `sysRolePermissions`:
--   `srp_sad_NameId`
--       `sysApplicationDetails` -> `sad_NameId`
--   `srp_sap_NameId`
--       `sysApplicationPermissions` -> `sap_NameId`
--   `srp_srd_RoleId`
--       `sysRoleDetails` -> `srd_RoleId`
--

--
-- Dumping data for table `sysRolePermissions`
--

INSERT INTO `sysRolePermissions` (`srp_IndexId`, `srp_srd_RoleId`, `srp_sad_NameId`, `srp_sap_NameId`) VALUES
(39, 'co', 'co', 'sysAppAccess'),
(36, 'construction', 'construction', 'sysAppAccess'),
(38, 'Plant', 'plant', 'sysAppAccess'),
(32, 'system_admin', 'base_admin', 'sysAppAccess'),
(33, 'system_admin', 'construction', 'sysAppAccess'),
(37, 'system_admin', 'deveolper_apptest', 'sysAppAccess'),
(31, 'system_dev', 'construction', 'sysAppAccess'),
(35, 'system_dev', 'deveolper_apptest', 'sysAppAccess');

-- --------------------------------------------------------

--
-- Table structure for table `sysSettingsKeyDefinitions`
--

CREATE TABLE `sysSettingsKeyDefinitions` (
  `sskd_IndexId` int UNSIGNED NOT NULL,
  `sskd_sad_NameId` varchar(255) NOT NULL,
  `sskd_TableName` varchar(255) NOT NULL,
  `sskd_SettingName` varchar(255) NOT NULL,
  `sskd_SettingType` enum('string','int','YN','list','hook') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'string',
  `sskd_SettingFatoryValue` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sskd_SettingDefaultValue` varchar(255) NOT NULL,
  `sskd_AllowOverride` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Settings';

--
-- RELATIONSHIPS FOR TABLE `sysSettingsKeyDefinitions`:
--   `sskd_sad_NameId`
--       `sysApplicationDetails` -> `sad_NameId`
--

--
-- Dumping data for table `sysSettingsKeyDefinitions`
--

INSERT INTO `sysSettingsKeyDefinitions` (`sskd_IndexId`, `sskd_sad_NameId`, `sskd_TableName`, `sskd_SettingName`, `sskd_SettingType`, `sskd_SettingFatoryValue`, `sskd_SettingDefaultValue`, `sskd_AllowOverride`) VALUES
(2, 'base_admin', 'sysSiteConfig', 'siteTitle', 'string', 'Laslo', 'Laslo Development', 'N'),
(3, 'base_admin', 'sysSiteConfig', 'logoPathFileName', 'string', 'logo.png', 'logo.png', 'N'),
(4, 'base_admin', 'sysSiteConfig', 'logoTitle', 'string', 'Laslo', 'Laslo', 'N'),
(5, 'base_admin', 'sysSiteConfig', 'logoUrlLink', 'string', '', '', 'N'),
(8, 'base_admin', 'sysSiteConfig', 'sessionTimeoutHours', 'int', '1', '4', 'N'),
(10, 'base_admin', 'sysSiteConfig', 'loginAttempsFailBlockAccount', 'int', '3', '3', 'N'),
(11, 'base_admin', 'sysSiteConfig', 'loginAttempsFailBlockIp', 'int', '9', '9', 'N'),
(12, 'base_admin', 'sysSiteConfig', 'loginFailBlockAccountInMinuites', 'int', '15', '15', 'N'),
(13, 'base_admin', 'sysSiteConfig', 'timeZoneOffset', 'hook', '-5', '-5', 'N'),
(15, 'base_admin', 'sysSiteConfig', 'siteLanguage', 'string', 'English', 'English', 'N'),
(16, 'base_admin', 'sysSiteConfig', 'logDaysRecordsStay', 'int', '90', '90', 'N'),
(18, 'base_admin', 'sysSiteConfig', 'timeDstStartMonth', 'int', '3', '3', 'N'),
(19, 'base_admin', 'sysSiteConfig', 'timeDstStartDay', 'int', '13', '13', 'N'),
(20, 'base_admin', 'sysSiteConfig', 'timeDstStopMonth', 'int', '1', '11', 'N'),
(21, 'base_admin', 'sysSiteConfig', 'timeDstStopDay', 'int', '6', '6', 'N'),
(24, 'base_admin', 'sysAppPreferences', 'theam', 'string', 'Clean', 'Clean', 'N'),
(63, 'base_admin', 'sysAppPreferences', 'appBarDisplayType', 'hook', 'Icon and Text', 'Icon and Text', 'N'),
(67, 'base_admin', 'sysAppPreferences', 'maxListMatches', 'int', '10', '10', 'Y'),
(68, 'base_admin', 'sysAppPreferences', 'defaultApp', 'hook', 'base_home', 'base_home', 'Y'),
(70, 'base_admin', 'sysAppPreferences', 'dateFormat', 'hook', 'YYYY/DD/MM', 'YYYY/DD/MM', 'N'),
(74, 'base_admin', 'sysSiteConfig', 'defaultSendingEmailSystem', 'hook', 'b39f57fc-e6ea-11e9-85c1-7446a0b52568', 'b39f57fc-e6ea-11e9-85c1-7446a0b52568', 'N'),
(75, 'base_admin', 'sysSiteConfig', 'favIconPathFileName', 'string', 'favicon.ico', 'custom/favicon_siftel.ico', 'N'),
(76, 'base_admin', 'sysSiteConfig', 'timeDstUsed', 'YN', 'Y', 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `sysUserDetails`
--

CREATE TABLE `sysUserDetails` (
  `sud_UserId` int UNSIGNED NOT NULL,
  `sud_Status` enum('A','I') NOT NULL DEFAULT 'I',
  `sud_NameId` varchar(255) NOT NULL,
  `sud_Password` varchar(255) NOT NULL,
  `sud_PasswordLastChangeUTC` varchar(50) NOT NULL,
  `sud_LastLoginUTC` varchar(50) NOT NULL,
  `sud_ForcePasswordChange` enum('Y','N') NOT NULL DEFAULT 'N',
  `sud_LastLoginFrom` varchar(255) NOT NULL,
  `sud_ExpiresUTC` varchar(50) NOT NULL,
  `sud_scd_CompanyId` int UNSIGNED NOT NULL,
  `sud_sdd_DepartmentId` int UNSIGNED NOT NULL,
  `sud_LanguageId` int UNSIGNED NOT NULL,
  `sud_NameFirst` varchar(255) NOT NULL,
  `sud_NameLast` varchar(255) NOT NULL,
  `sud_PrimaryEmail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of users in the system.';

--
-- RELATIONSHIPS FOR TABLE `sysUserDetails`:
--   `sud_scd_CompanyId`
--       `sysCompanyDetails` -> `scd_CompanyId`
--   `sud_sdd_DepartmentId`
--       `sysDepartmentDetails` -> `sdd_DepartmentId`
--

--
-- Dumping data for table `sysUserDetails`
--

INSERT INTO `sysUserDetails` (`sud_UserId`, `sud_Status`, `sud_NameId`, `sud_Password`, `sud_PasswordLastChangeUTC`, `sud_LastLoginUTC`, `sud_ForcePasswordChange`, `sud_LastLoginFrom`, `sud_ExpiresUTC`, `sud_scd_CompanyId`, `sud_sdd_DepartmentId`, `sud_LanguageId`, `sud_NameFirst`, `sud_NameLast`, `sud_PrimaryEmail`) VALUES
(11, 'A', 'system_admin', '$2y$10$dJon.6HHZ7nO2bQNknrPgeg7oZbdxRUtPpfuK6ad7yZOEfKmLHaLO', '2022-09-29T16:14:00Z-05:00', '2024-06-05T15:22:16Z-05:00', 'N', '192.168.40.25', '12/1/2030', 1, 1, 1, 'system', 'admin', 'none@none.com'),
(12, 'A', 'system_dev', '$2y$10$7gIb5KjUGgvXHQM9XHgrAeNWxYZINIXtYUJN8YwhSobWTCm0ygTYa', '2023-03-23T08:18:32Z-05:00', '2024-06-05T15:20:18Z-05:00', 'N', '192.168.40.25', '12/1/2030', 1, 1, 1, 'system', 'dev', 'none@none.com'),
(13, 'A', 'test1', '$2y$10$OT8HUZn6nkooXZ6HpazqQezr51eJEvfoQVx5rQjoq68Myp6qSr8QS', '2023-03-23T13:57:50Z-05:00', '2023-03-23T13:57:39Z-05:00', 'N', '172.25.12.26', '12/1/2030', 1, 1, 1, 'test1', 'test1', 'none@none.com');

-- --------------------------------------------------------

--
-- Table structure for table `sysUserPreferences`
--

CREATE TABLE `sysUserPreferences` (
  `sup_IndexId` int UNSIGNED NOT NULL,
  `sup_sud_NameId` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sup_sad_NameId` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sup_sskd_TableName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sup_sskd_SettingName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sup_Value` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `sysUserPreferences`:
--   `sup_sad_NameId`
--       `sysSettingsKeyDefinitions` -> `sskd_sad_NameId`
--   `sup_sskd_TableName`
--       `sysSettingsKeyDefinitions` -> `sskd_TableName`
--   `sup_sskd_SettingName`
--       `sysSettingsKeyDefinitions` -> `sskd_SettingName`
--   `sup_sud_NameId`
--       `sysUserDetails` -> `sud_NameId`
--

--
-- Dumping data for table `sysUserPreferences`
--

INSERT INTO `sysUserPreferences` (`sup_IndexId`, `sup_sud_NameId`, `sup_sad_NameId`, `sup_sskd_TableName`, `sup_sskd_SettingName`, `sup_Value`) VALUES
(5, 'system_dev', 'base_admin', 'sysAppPreferences', 'defaultApp', 'deveolper_apptest');

-- --------------------------------------------------------

--
-- Table structure for table `sysUserRoles`
--

CREATE TABLE `sysUserRoles` (
  `sur_IndexId` int UNSIGNED NOT NULL,
  `sur_sud_NameId` varchar(255) NOT NULL,
  `sur_srd_RoleId` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Roles Assigned to users.';

--
-- RELATIONSHIPS FOR TABLE `sysUserRoles`:
--   `sur_srd_RoleId`
--       `sysRoleDetails` -> `srd_RoleId`
--   `sur_sud_NameId`
--       `sysUserDetails` -> `sud_NameId`
--

--
-- Dumping data for table `sysUserRoles`
--

INSERT INTO `sysUserRoles` (`sur_IndexId`, `sur_sud_NameId`, `sur_srd_RoleId`) VALUES
(8, 'system_admin', 'system_admin'),
(12, 'system_dev', 'co'),
(10, 'system_dev', 'system_dev');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sysApplicationDetails`
--
ALTER TABLE `sysApplicationDetails`
  ADD PRIMARY KEY (`sad_IndexId`),
  ADD UNIQUE KEY `sad_NameId` (`sad_NameId`);

--
-- Indexes for table `sysApplicationJobs`
--
ALTER TABLE `sysApplicationJobs`
  ADD PRIMARY KEY (`saj_UUID`),
  ADD KEY `FK_sysApplicationJobs_sysApplicationDetails` (`saj_sad_NameId`);

--
-- Indexes for table `sysApplicationPermissions`
--
ALTER TABLE `sysApplicationPermissions`
  ADD PRIMARY KEY (`sap_IndexId`),
  ADD UNIQUE KEY `sap_NameId_sap_sad_NameId` (`sap_NameId`,`sap_sad_NameId`),
  ADD KEY `FK_sysApplicationPermissions_sysApplicationDetails` (`sap_sad_NameId`);

--
-- Indexes for table `sysCompanyDetails`
--
ALTER TABLE `sysCompanyDetails`
  ADD PRIMARY KEY (`scd_CompanyId`),
  ADD UNIQUE KEY `scd_Name` (`scd_Name`);

--
-- Indexes for table `sysDepartmentDetails`
--
ALTER TABLE `sysDepartmentDetails`
  ADD PRIMARY KEY (`sdd_DepartmentId`),
  ADD KEY `FK_sysDepartmentDetails_sysCompanyList` (`sdd_scd_CompanyId`);

--
-- Indexes for table `sysDepartmentPreferences`
--
ALTER TABLE `sysDepartmentPreferences`
  ADD PRIMARY KEY (`sdp_IndexId`),
  ADD KEY `FK_sysDepartmentPreferences_sysDepartmentDetails` (`sdp_sdd_DepartmentId`),
  ADD KEY `FK_sysDepartmentPreferences_sysSettingsKeyDefinitions` (`sdp_sad_NameId`,`sdp_sskd_TableName`,`sdp_sskd_SettingName`);

--
-- Indexes for table `sysEmailAddresses`
--
ALTER TABLE `sysEmailAddresses`
  ADD PRIMARY KEY (`sea_UUID`),
  ADD UNIQUE KEY `sea_Address` (`sea_Address`);

--
-- Indexes for table `sysEmailLists`
--
ALTER TABLE `sysEmailLists`
  ADD PRIMARY KEY (`sel_seld_UUID`,`sel_sea_UUID`),
  ADD KEY `FK_sysEmailLists_sysEmailAddresses` (`sel_sea_UUID`);

--
-- Indexes for table `sysEmailListsDetails`
--
ALTER TABLE `sysEmailListsDetails`
  ADD PRIMARY KEY (`seld_UUID`),
  ADD UNIQUE KEY `seld_NameId` (`seld_NameId`);

--
-- Indexes for table `sysEmailServers`
--
ALTER TABLE `sysEmailServers`
  ADD PRIMARY KEY (`ses_UUID`);

--
-- Indexes for table `sysJobScheduler`
--
ALTER TABLE `sysJobScheduler`
  ADD PRIMARY KEY (`sjs_UUID`),
  ADD KEY `FK_sysJobScheduler_sysApplicationJobs` (`sjs_saj_UUID`),
  ADD KEY `FK_sysJobScheduler_sysEmailServers` (`sjs_ses_UUID`),
  ADD KEY `FK_sysJobScheduler_sysEmailListsDetails` (`sjs_seld_UUID_To`),
  ADD KEY `FK_sysJobScheduler_sysEmailListsDetails_2` (`sjs_seld_UUID_Bcc`),
  ADD KEY `FK_sysJobScheduler_sysEmailListsDetails_3` (`sjs_seld_UUID_Cc`);

--
-- Indexes for table `sysListLists`
--
ALTER TABLE `sysListLists`
  ADD PRIMARY KEY (`sll_IndexId`),
  ADD UNIQUE KEY `sll_ApplicationName_sll_TableName_sll_TableKey` (`sll_sad_ApplicationName`,`sll_TableName`,`sll_TableKey`);

--
-- Indexes for table `sysPreferencesValues`
--
ALTER TABLE `sysPreferencesValues`
  ADD UNIQUE KEY `unq_` (`spv_OwnerType`,`spv_OwnerId`,`spv_sskd_NameId`,`spv_sskd_TableName`,`spv_sskd_SettingName`),
  ADD KEY `FK_sysPreferencesValues_sysSettingsKeyDefinitions` (`spv_sskd_NameId`,`spv_sskd_TableName`,`spv_sskd_SettingName`);

--
-- Indexes for table `sysRoleDetails`
--
ALTER TABLE `sysRoleDetails`
  ADD PRIMARY KEY (`srd_IndexId`),
  ADD UNIQUE KEY `Unq_srd_RoleId` (`srd_RoleId`);

--
-- Indexes for table `sysRolePermissions`
--
ALTER TABLE `sysRolePermissions`
  ADD PRIMARY KEY (`srp_IndexId`),
  ADD UNIQUE KEY `srp_srd_RoleId_srp_sap_sad_NameId_srp_sap_NameId` (`srp_srd_RoleId`,`srp_sad_NameId`,`srp_sap_NameId`),
  ADD KEY `FK_sysRolePermissions_sysApplicationDetails` (`srp_sad_NameId`),
  ADD KEY `FK_sysRolePermissions_sysApplicationPermissions` (`srp_sap_NameId`);

--
-- Indexes for table `sysSettingsKeyDefinitions`
--
ALTER TABLE `sysSettingsKeyDefinitions`
  ADD PRIMARY KEY (`sskd_IndexId`),
  ADD UNIQUE KEY `sskd_ApplicationName_sskd_TableName_sskd_SettingName` (`sskd_sad_NameId`,`sskd_TableName`,`sskd_SettingName`);

--
-- Indexes for table `sysUserDetails`
--
ALTER TABLE `sysUserDetails`
  ADD PRIMARY KEY (`sud_UserId`),
  ADD UNIQUE KEY `unq_sud_NameId` (`sud_NameId`),
  ADD KEY `FK_sysUserDetails_sysCompanyList` (`sud_scd_CompanyId`),
  ADD KEY `FK_sysUserDetails_sysDepartmentDetails` (`sud_sdd_DepartmentId`);

--
-- Indexes for table `sysUserPreferences`
--
ALTER TABLE `sysUserPreferences`
  ADD PRIMARY KEY (`sup_IndexId`) USING BTREE,
  ADD KEY `FK_sysUserPreferences_sysSettingsKeyDefinitions` (`sup_sad_NameId`,`sup_sskd_TableName`,`sup_sskd_SettingName`) USING BTREE,
  ADD KEY `FK_sysUserPreferences_sysUserDetails` (`sup_sud_NameId`) USING BTREE;

--
-- Indexes for table `sysUserRoles`
--
ALTER TABLE `sysUserRoles`
  ADD PRIMARY KEY (`sur_IndexId`),
  ADD UNIQUE KEY `sur_sud_NameId_sur_srd_RoleId` (`sur_sud_NameId`,`sur_srd_RoleId`),
  ADD KEY `FK_sysUserRoles_sysRoleDetails` (`sur_srd_RoleId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sysApplicationDetails`
--
ALTER TABLE `sysApplicationDetails`
  MODIFY `sad_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sysApplicationPermissions`
--
ALTER TABLE `sysApplicationPermissions`
  MODIFY `sap_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sysCompanyDetails`
--
ALTER TABLE `sysCompanyDetails`
  MODIFY `scd_CompanyId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sysDepartmentDetails`
--
ALTER TABLE `sysDepartmentDetails`
  MODIFY `sdd_DepartmentId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sysDepartmentPreferences`
--
ALTER TABLE `sysDepartmentPreferences`
  MODIFY `sdp_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sysListLists`
--
ALTER TABLE `sysListLists`
  MODIFY `sll_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sysRoleDetails`
--
ALTER TABLE `sysRoleDetails`
  MODIFY `srd_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sysRolePermissions`
--
ALTER TABLE `sysRolePermissions`
  MODIFY `srp_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `sysSettingsKeyDefinitions`
--
ALTER TABLE `sysSettingsKeyDefinitions`
  MODIFY `sskd_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `sysUserDetails`
--
ALTER TABLE `sysUserDetails`
  MODIFY `sud_UserId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sysUserPreferences`
--
ALTER TABLE `sysUserPreferences`
  MODIFY `sup_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sysUserRoles`
--
ALTER TABLE `sysUserRoles`
  MODIFY `sur_IndexId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sysApplicationJobs`
--
ALTER TABLE `sysApplicationJobs`
  ADD CONSTRAINT `FK_sysApplicationJobs_sysApplicationDetails` FOREIGN KEY (`saj_sad_NameId`) REFERENCES `sysApplicationDetails` (`sad_NameId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sysApplicationPermissions`
--
ALTER TABLE `sysApplicationPermissions`
  ADD CONSTRAINT `FK_sysApplicationPermissions_sysApplicationDetails` FOREIGN KEY (`sap_sad_NameId`) REFERENCES `sysApplicationDetails` (`sad_NameId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sysDepartmentDetails`
--
ALTER TABLE `sysDepartmentDetails`
  ADD CONSTRAINT `FK_sysDepartmentDetails_sysCompanyList` FOREIGN KEY (`sdd_scd_CompanyId`) REFERENCES `sysCompanyDetails` (`scd_CompanyId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sysDepartmentPreferences`
--
ALTER TABLE `sysDepartmentPreferences`
  ADD CONSTRAINT `FK_sysDepartmentPreferences_sysDepartmentDetails` FOREIGN KEY (`sdp_sdd_DepartmentId`) REFERENCES `sysDepartmentDetails` (`sdd_DepartmentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_sysDepartmentPreferences_sysSettingsKeyDefinitions` FOREIGN KEY (`sdp_sad_NameId`,`sdp_sskd_TableName`,`sdp_sskd_SettingName`) REFERENCES `sysSettingsKeyDefinitions` (`sskd_sad_NameId`, `sskd_TableName`, `sskd_SettingName`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sysEmailLists`
--
ALTER TABLE `sysEmailLists`
  ADD CONSTRAINT `FK_sysEmailLists_sysEmailAddresses` FOREIGN KEY (`sel_sea_UUID`) REFERENCES `sysEmailAddresses` (`sea_UUID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_sysEmailLists_sysEmailListsDetails` FOREIGN KEY (`sel_seld_UUID`) REFERENCES `sysEmailListsDetails` (`seld_UUID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sysJobScheduler`
--
ALTER TABLE `sysJobScheduler`
  ADD CONSTRAINT `FK_sysJobScheduler_sysApplicationJobs` FOREIGN KEY (`sjs_saj_UUID`) REFERENCES `sysApplicationJobs` (`saj_UUID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_sysJobScheduler_sysEmailListsDetails` FOREIGN KEY (`sjs_seld_UUID_To`) REFERENCES `sysEmailListsDetails` (`seld_UUID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_sysJobScheduler_sysEmailListsDetails_2` FOREIGN KEY (`sjs_seld_UUID_Bcc`) REFERENCES `sysEmailListsDetails` (`seld_UUID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_sysJobScheduler_sysEmailListsDetails_3` FOREIGN KEY (`sjs_seld_UUID_Cc`) REFERENCES `sysEmailListsDetails` (`seld_UUID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_sysJobScheduler_sysEmailServers` FOREIGN KEY (`sjs_ses_UUID`) REFERENCES `sysEmailServers` (`ses_UUID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sysListLists`
--
ALTER TABLE `sysListLists`
  ADD CONSTRAINT `FK_sysListLists_sysApplicationDetails` FOREIGN KEY (`sll_sad_ApplicationName`) REFERENCES `sysApplicationDetails` (`sad_NameId`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `sysPreferencesValues`
--
ALTER TABLE `sysPreferencesValues`
  ADD CONSTRAINT `FK_sysPreferencesValues_sysSettingsKeyDefinitions` FOREIGN KEY (`spv_sskd_NameId`,`spv_sskd_TableName`,`spv_sskd_SettingName`) REFERENCES `sysSettingsKeyDefinitions` (`sskd_sad_NameId`, `sskd_TableName`, `sskd_SettingName`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `sysRolePermissions`
--
ALTER TABLE `sysRolePermissions`
  ADD CONSTRAINT `FK_sysRolePermissions_sysApplicationDetails` FOREIGN KEY (`srp_sad_NameId`) REFERENCES `sysApplicationDetails` (`sad_NameId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_sysRolePermissions_sysApplicationPermissions` FOREIGN KEY (`srp_sap_NameId`) REFERENCES `sysApplicationPermissions` (`sap_NameId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_sysRolePermissions_sysRoleDetails` FOREIGN KEY (`srp_srd_RoleId`) REFERENCES `sysRoleDetails` (`srd_RoleId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sysSettingsKeyDefinitions`
--
ALTER TABLE `sysSettingsKeyDefinitions`
  ADD CONSTRAINT `FK_sysSettingsKeyDefinitions_sysApplicationDetails` FOREIGN KEY (`sskd_sad_NameId`) REFERENCES `sysApplicationDetails` (`sad_NameId`) ON UPDATE CASCADE;

--
-- Constraints for table `sysUserDetails`
--
ALTER TABLE `sysUserDetails`
  ADD CONSTRAINT `FK_sysUserDetails_sysCompanyList` FOREIGN KEY (`sud_scd_CompanyId`) REFERENCES `sysCompanyDetails` (`scd_CompanyId`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_sysUserDetails_sysDepartmentDetails` FOREIGN KEY (`sud_sdd_DepartmentId`) REFERENCES `sysDepartmentDetails` (`sdd_DepartmentId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sysUserPreferences`
--
ALTER TABLE `sysUserPreferences`
  ADD CONSTRAINT `FK_sysUserPreferences_sysSettingsKeyDefinitions` FOREIGN KEY (`sup_sad_NameId`,`sup_sskd_TableName`,`sup_sskd_SettingName`) REFERENCES `sysSettingsKeyDefinitions` (`sskd_sad_NameId`, `sskd_TableName`, `sskd_SettingName`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_sysUserPreferences_sysUserDetails` FOREIGN KEY (`sup_sud_NameId`) REFERENCES `sysUserDetails` (`sud_NameId`) ON DELETE CASCADE;

--
-- Constraints for table `sysUserRoles`
--
ALTER TABLE `sysUserRoles`
  ADD CONSTRAINT `FK_sysUserRoles_sysRoleDetails` FOREIGN KEY (`sur_srd_RoleId`) REFERENCES `sysRoleDetails` (`srd_RoleId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_sysUserRoles_sysUserDetails` FOREIGN KEY (`sur_sud_NameId`) REFERENCES `sysUserDetails` (`sud_NameId`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
