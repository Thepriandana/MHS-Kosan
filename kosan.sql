-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2020 at 04:27 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `allocation`
--

INSERT INTO `allocation` (`allocationID`, `applicationID`, `unitNo`, `formDate`, `duration`, `endDate`) VALUES
(1, 1, 3, '2020-03-18', 12, '2021-03-18');

-- --------------------------------------------------------

--
-- Table structure for table `applicant`
--

CREATE TABLE IF NOT EXISTS `applicant` (
  `applicantID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `monthlyIncome` float NOT NULL,
  PRIMARY KEY (`applicantID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `applicant`
--

INSERT INTO `applicant` (`applicantID`, `userID`, `email`, `monthlyIncome`) VALUES
(1, 1, 'a@a.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`applicationID`, `applicantID`, `residenceID`, `applicationDate`, `requiredMonth`, `requiredYear`, `status`) VALUES
(1, 1, 1, '2020-03-31 01:00:13', 1, 1, 'Approve');

-- --------------------------------------------------------

--
-- Table structure for table `housingofficer`
--

CREATE TABLE IF NOT EXISTS `housingofficer` (
  `staffID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`staffID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `housingofficer`
--

INSERT INTO `housingofficer` (`staffID`, `userID`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `residence`
--

CREATE TABLE IF NOT EXISTS `residence` (
  `residenceID` int(11) NOT NULL AUTO_INCREMENT,
  `staffID` int(11) NOT NULL,
  `address` varchar(200) NOT NULL,
  `numUnits` int(11) NOT NULL,
  `sizePerUnit` float NOT NULL,
  `monthlyRental` float NOT NULL,
  PRIMARY KEY (`residenceID`),
  KEY `staffID` (`staffID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `residence`
--

INSERT INTO `residence` (`residenceID`, `staffID`, `address`, `numUnits`, `sizePerUnit`, `monthlyRental`) VALUES
(1, 1, 'c', 5, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
  `unitNo` int(11) NOT NULL AUTO_INCREMENT,
  `residenceID` int(11) NOT NULL,
  `availability` varchar(11) NOT NULL,
  PRIMARY KEY (`unitNo`),
  KEY `residenceID` (`residenceID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unitNo`, `residenceID`, `availability`) VALUES
(3, 1, 'used'),
(4, 1, 'available'),
(5, 1, 'available'),
(6, 1, 'used'),
(7, 1, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(256) NOT NULL,
  `fullname` varchar(35) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `fullname`) VALUES
(1, 'a', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'c'),
(2, 'b', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'a');

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
