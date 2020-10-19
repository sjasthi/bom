-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2020 at 12:21 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bom_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE `preferences` (
  `preference_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` varchar(100) NOT NULL,
  `comments` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `preferences`
--

INSERT INTO `preferences` (`preference_id`, `name`, `type`, `value`, `comments`) VALUES
(1, 'SYSTEM_BOMS', 'STRING', 'BOM-100,BOM-101,BOM-104', 'Controls the system scope BOM display'),
(2, 'gantt_end', 'DATE', '2022-08-01', 'how far out the gantt chart should be displayed?'),
(3, 'gantt_start', 'DATE', '2018-08-01', 'start date for the gantt chart '),
(4, 'gantt_status', 'STRING', '\'Active\', \'Build Env Avail\', \'Build/Kit Avail\', \'Cancelled\', \'Completed\', \'Draft\', \'Released\', \'Requ', '\'Active\', \'Completed\', \'Draft\', \'Released\''),
(5, 'gantt_type', 'STRING', '\'Major\', \'Minor\'', '\'Async\', \'Major\', \'Minor\', \'Patch\'');

-- --------------------------------------------------------

--
-- Table structure for table `releases`
--

CREATE TABLE `releases` (
  `id` varchar(10) NOT NULL,
  `name` varchar(89) DEFAULT NULL,
  `type` varchar(6) DEFAULT NULL,
  `status` varchar(9) DEFAULT NULL,
  `open_date` varchar(10) DEFAULT NULL,
  `dependency_date` varchar(10) DEFAULT NULL,
  `freeze_date` date DEFAULT NULL,
  `content_date` varchar(10) DEFAULT NULL,
  `rtm_date` varchar(10) DEFAULT NULL,
  `manager` varchar(14) DEFAULT NULL,
  `author` varchar(15) DEFAULT NULL,
  `app_id` varchar(7) DEFAULT NULL,
  `tag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `releases`
--

INSERT INTO `releases` (`id`, `name`, `type`, `status`, `open_date`, `dependency_date`, `freeze_date`, `content_date`, `rtm_date`, `manager`, `author`, `app_id`, `tag`) VALUES
('ICS-201684', 'SAFe Project V.5.6.8', 'Async ', 'Draft', '2020-10-01', '2020-08-23', NULL, '2020-10-18', '2020-12-06', 'Brill, Barbara', 'Ram, Christina', 'BOM-107', ''),
('ICS-201685', 'SAFe Project V.5.6.9', 'Async ', 'Draft', '2021-10-01', '2021-08-23', NULL, '2021-10-18', '2021-12-06', 'Brill, Barbara', 'Ram, Christina', 'BOM-112', ''),
('ICS-201689', 'SAFe Project V.5.6.7', 'Async ', 'Active', '2019-10-01', '2019-08-23', NULL, '2019-10-18', '2019-12-06', 'Brill, Barbara', 'Ram, Christina', 'BOM-102', ''),
('ICS-201812', 'QuizMaster 1.1', 'Major', 'Completed', '2019-08-23', '2019-10-18', NULL, '', '2019-08-14', 'Jasthi, Siva', 'Knight, Mark', 'BOM-100', 'release'),
('ICS-201814', 'QuizMaster 1.2', 'Major', 'Draft', '2020-08-23', '2020-10-18', NULL, '', '2020-08-14', 'Jasthi, Siva', 'Knight, Mark', 'BOM-105', 'active'),
('ICS-201815', 'QuizMaster That Works in English, Telugu, Hindi, Kannada and Other Indic Languages V 2020', 'Major', 'Draft', '2021-08-23', '2021-10-18', NULL, '', '2021-08-14', 'Jasthi, Siva', 'Knight, Mark', 'BOM-110', ''),
('ICS-201944', 'Bingo 2.4', 'Minor', 'Draft', '2020-10-18', '', NULL, '', '2020-09-05', 'Doe, John', 'Doe, Jane', 'BOM-106', ''),
('ICS-201945', 'Bingo 2.3', 'Minor', 'Draft', '2019-10-18', '', NULL, '', '2019-09-05', 'Doe, John', 'Doe, Jane', 'BOM-101', ''),
('ICS-201955', 'Bingo 2.5', 'Minor', 'Draft', '2021-10-18', '', NULL, '', '2021-09-05', 'Doe, John', 'Doe, Jane', 'BOM-111', ''),
('ICS-789084', 'Registration System V.2020', 'Async ', 'Draft', '2020-10-01', '2020-08-23', NULL, '2020-10-18', '2020-12-06', 'Drew, Andy', 'Peterson, Rocky', 'BOM-108', ''),
('ICS-789085', 'Registration System V.2020.1', 'Async ', 'Draft', '2021-10-01', '2021-08-23', NULL, '2021-10-18', '2021-12-06', 'Drew, Andy', 'Peterson, Rocky', 'BOM-113', ''),
('ICS-789089', 'Registration System V.2019', 'Async ', 'Released', '2019-10-01', '2019-08-23', NULL, '2019-10-18', '2019-12-06', 'Drew, Andy', 'Peterson, Rocky', 'BOM-103', ''),
('ICS-898984', 'Word Explorer 2021', 'Patch', 'Draft', '2020-10-01', '2020-08-23', NULL, '2020-10-18', '2020-12-06', 'Jasthi, Siva', 'Jasthi, Siva', 'BOM-109', ''),
('ICS-898985', 'Word Explorer 2022', 'Patch', 'Draft', '2021-10-01', '2021-08-23', NULL, '2021-10-18', '2021-12-06', 'Jasthi, Siva', 'Jasthi, Siva', 'BOM-114', ''),
('ICS-898989', 'Word Explorer 2020', 'Patch', 'Completed', '2019-10-01', '2019-08-23', NULL, '2019-10-18', '2019-12-06', 'Jasthi, Siva', 'Jasthi, Siva', 'BOM-104', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `sbom`
--

CREATE TABLE `sbom` (
  `row_id` int(6) NOT NULL,
  `app_id` varchar(15) NOT NULL,
  `app_name` varchar(100) NOT NULL,
  `app_version` varchar(15) NOT NULL,
  `cmp_id` varchar(15) NOT NULL,
  `cmp_name` varchar(100) NOT NULL,
  `cmp_version` varchar(15) NOT NULL,
  `cmp_type` varchar(15) NOT NULL,
  `app_status` varchar(15) NOT NULL,
  `cmp_status` varchar(15) NOT NULL,
  `request_id` varchar(15) NOT NULL,
  `request_date` date NOT NULL,
  `request_status` varchar(15) NOT NULL,
  `request_step` varchar(30) NOT NULL,
  `notes` varchar(100) DEFAULT NULL,
  `requestor` varchar(100) NOT NULL,
  `color` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sbom`
--

INSERT INTO `sbom` (`row_id`, `app_id`, `app_name`, `app_version`, `cmp_id`, `cmp_name`, `cmp_version`, `cmp_type`, `app_status`, `cmp_status`, `request_id`, `request_date`, `request_status`, `request_step`, `notes`, `requestor`, `color`) VALUES
(24093, 'BOM-100', 'QuizMaster', '1.1', '101.1', 'DB_Layer', '2.3', 'internal', 'released', 'released', 'ABC_1', '2019-01-02', 'approved', 'approval step', 'blah', '', 'red'),
(24094, 'BOM-100', 'QuizMaster', '1.1', '101.2', 'Jquery', '4.3', 'open source', 'released', 'approved', 'ABC_2', '2019-01-03', 'approved', 'approval step', 'blah', '', 'yellow'),
(24095, 'BOM-100', 'QuizMaster', '1.1', '101.3', 'Bootstrap', '8.5.c', 'open source', 'released', 'pending', 'ABC_3', '2019-01-04', 'approved', 'approval step', 'blah', '', 'yellow'),
(24096, 'BOM-100', 'QuizMaster', '1.1', '101.4', 'IconFinder', '2019', 'commercial', 'released', 'submitted', 'ABC_4', '2019-01-05', 'approved', 'approval step', 'blah', '', 'yellow'),
(24097, 'BOM-100', 'QuizMaster', '1.1', '101.5', 'Excel', '2019', 'commercial', 'released', 'in_review', 'ABC_5', '2019-01-06', 'approved', 'approval step', 'blah', '', 'yellow'),
(24098, '101.1', 'DB_Layer', '2.3', '101.1.1', 'DB_Layer_MySQL', 'v1.0', 'internal', 'released', 'released', 'ABC_6', '2019-01-07', 'approved', 'approval step', 'blah', '', 'yellow'),
(24099, '101.1', 'DB_Layer', '2.3', '101.1.2', 'DB_Layer_DB2', 'v1.0', 'internal', 'released', 'released', 'ABC_7', '2019-01-08', 'approved', 'approval step', 'blah', '', 'yellow'),
(24100, '101.1', 'DB_Layer', '2.3', '101.1.4', 'DB_Layer_Ingress', 'v1.0', 'internal', 'released', 'released', 'ABC_8', '2019-01-09', 'approved', 'approval step', 'blah', '', 'yellow'),
(24101, 'BOM-104', 'QuizMaster', '2.2', '202.2', 'DB_Layer', '2.3', 'internal', 'released', 'released', 'ABC_9', '2019-01-10', 'approved', 'approval step', 'blah', '', 'red'),
(24102, 'BOM-104', 'QuizMaster', '2.2', '202.2', 'Jquery', '4.3', 'open source', 'released', 'approved', 'ABC_10', '2019-01-11', 'approved', 'approval step', 'blah', '', 'red'),
(24103, 'BOM-104', 'QuizMaster', '2.2', '202.3', 'Bootstrap', '8.5.c', 'open source', 'released', 'pending', 'ABC_11', '2019-01-12', 'approved', 'approval step', 'blah', '', 'yellow'),
(24104, 'BOM-104', 'QuizMaster', '2.2', '202.4', 'IconFinder', '2029', 'commercial', 'released', 'submitted', 'ABC_12', '2019-01-13', 'approved', 'approval step', 'blah', '', 'yellow'),
(24105, 'BOM-104', 'QuizMaster', '2.2', '202.5', 'Excel', '2029', 'commercial', 'released', 'in_review', 'ABC_13', '2019-01-14', 'approved', 'approval step', 'blah', '', 'yellow'),
(24106, '202.2', 'DB_Layer', '2.3', '202.2.2', 'DB_Layer_Maria', 'v2.0', 'internal', 'released', 'released', 'ABC_14', '2019-01-15', 'approved', 'approval step', 'blah', '', 'yellow'),
(24107, '202.2', 'DB_Layer', '2.3', '202.2.2', 'DB_Layer_DB2', 'v2.0', 'internal', 'released', 'released', 'ABC_15', '2019-01-16', 'approved', 'approval step', 'blah', '', 'yellow'),
(24108, '202.2', 'DB_Layer', '2.3', '202.2.3', 'DB_Layer_Oracle', 'v2.0', 'internal', 'released', 'released', 'ABC_16', '2019-01-17', 'approved', 'approval step', 'blah', '', 'yellow'),
(24109, '202.2', 'DB_Layer', '2.3', '202.2.4', 'DB_Layer_Ingress', 'v2.0', 'internal', 'released', 'released', 'ABC_17', '2019-01-18', 'approved', 'approval step', 'blah', '', 'yellow'),
(24110, '202.2', 'DB_Layer', '2.3', '202.2.5', 'DB_Layer_MS_SQL', 'v2.0', 'internal', 'released', 'released', 'ABC_18', '2019-01-19', 'approved', 'approval step', 'blah', '', 'yellow'),
(24111, '202.2', 'DB_Layer', '2.3', '202.2.5', 'DB_Layer_MS_SQL', 'v2.0', 'internal', 'released', 'released', 'ABC_18', '2019-01-19', 'approved', 'approval step', 'blah', '', 'yellow');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(75) NOT NULL,
  `hash` varchar(200) NOT NULL,
  `active` varchar(10) NOT NULL,
  `role` varchar(20) NOT NULL,
  `modified_time` date NOT NULL,
  `created_time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `hash`, `active`, `role`, `modified_time`, `created_time`) VALUES
(1, 'Siva', 'Jasthi', 'siva.jasthi@gmail.com', '$2y$10$zFAG5GBNtf.5BpowMqZSputSLeG8OzfKACpjAMsePjZhu.TnvU/Bu', 'yes', 'admin', '0000-00-00', '0000-00-00'),
(2, 'admin', 'admin', 'admin@gmail.com', '$2y$10$zFAG5GBNtf.5BpowMqZSputSLeG8OzfKACpjAMsePjZhu.TnvU/Bu', 'yes', 'admin', '0000-00-00', '0000-00-00'),
(3, 'ICS', '325', 'ics325@metrostate.edu', '$2y$10$zFAG5GBNtf.5BpowMqZSputSLeG8OzfKACpjAMsePjZhu.TnvU/Bu', 'yes', 'admin', '0000-00-00', '0000-00-00'),
(4, 'ICS', '499', 'ics499@metrostate.edu', '$2y$10$zFAG5GBNtf.5BpowMqZSputSLeG8OzfKACpjAMsePjZhu.TnvU/Bu', 'yes', 'admin', '0000-00-00', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`preference_id`);

--
-- Indexes for table `releases`
--
ALTER TABLE `releases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sbom`
--
ALTER TABLE `sbom`
  ADD PRIMARY KEY (`row_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sbom`
--
ALTER TABLE `sbom`
  MODIFY `row_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24112;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
