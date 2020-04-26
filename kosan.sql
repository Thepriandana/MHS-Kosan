-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2020 at 05:54 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kosan`
--
CREATE DATABASE IF NOT EXISTS `kosan` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `kosan`;

-- --------------------------------------------------------

--
-- Table structure for table `allocation`
--

DROP TABLE IF EXISTS `allocation`;
CREATE TABLE IF NOT EXISTS `allocation` (
  `allocationID` int(11) NOT NULL AUTO_INCREMENT,
  `applicationID` int(11) NOT NULL,
  `unitNo` int(11) NOT NULL,
  `formDate` date NOT NULL,
  `duration` int(11) NOT NULL,
  `endDate` date NOT NULL,
  PRIMARY KEY (`allocationID`),
  KEY `applicationID` (`applicationID`),
  KEY `unitNo` (`unitNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `applicant`
--

DROP TABLE IF EXISTS `applicant`;
CREATE TABLE IF NOT EXISTS `applicant` (
  `applicantID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `monthlyIncome` float NOT NULL,
  PRIMARY KEY (`applicantID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
CREATE TABLE IF NOT EXISTS `application` (
  `applicationID` int(11) NOT NULL AUTO_INCREMENT,
  `applicantID` int(11) NOT NULL,
  `residenceID` int(11) NOT NULL,
  `applicationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `requiredMonth` int(11) NOT NULL,
  `requiredYear` int(11) NOT NULL,
  `status` varchar(11) NOT NULL,
  PRIMARY KEY (`applicationID`),
  KEY `applicantID` (`applicantID`),
  KEY `residenceID` (`residenceID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `housingofficer`
--

DROP TABLE IF EXISTS `housingofficer`;
CREATE TABLE IF NOT EXISTS `housingofficer` (
  `staffID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`staffID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `residence`
--

DROP TABLE IF EXISTS `residence`;
CREATE TABLE IF NOT EXISTS `residence` (
  `residenceID` int(11) NOT NULL AUTO_INCREMENT,
  `staffID` int(11) NOT NULL,
  `address` varchar(200) NOT NULL,
  `numUnits` int(11) NOT NULL,
  `sizePerUnit` float NOT NULL,
  `monthlyRental` float NOT NULL,
  PRIMARY KEY (`residenceID`),
  KEY `staffID` (`staffID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
CREATE TABLE IF NOT EXISTS `unit` (
  `unitNo` int(11) NOT NULL AUTO_INCREMENT,
  `residenceID` int(11) NOT NULL,
  `availability` varchar(11) NOT NULL,
  PRIMARY KEY (`unitNo`),
  KEY `residenceID` (`residenceID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(256) NOT NULL,
  `fullname` varchar(35) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocation`
--
ALTER TABLE `allocation`
  ADD CONSTRAINT `allocation_ibfk_1` FOREIGN KEY (`applicationID`) REFERENCES `application` (`applicationID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `allocation_ibfk_2` FOREIGN KEY (`unitNo`) REFERENCES `unit` (`unitNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `applicant`
--
ALTER TABLE `applicant`
  ADD CONSTRAINT `applicant_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`applicantID`) REFERENCES `applicant` (`applicantID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`residenceID`) REFERENCES `residence` (`residenceID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `housingofficer`
--
ALTER TABLE `housingofficer`
  ADD CONSTRAINT `housingofficer_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `residence`
--
ALTER TABLE `residence`
  ADD CONSTRAINT `residence_ibfk_1` FOREIGN KEY (`staffID`) REFERENCES `housingofficer` (`staffID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `unit`
--
ALTER TABLE `unit`
  ADD CONSTRAINT `unit_ibfk_1` FOREIGN KEY (`residenceID`) REFERENCES `residence` (`residenceID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
