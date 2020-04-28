-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 
-- サーバのバージョン： 10.1.28-MariaDB
-- PHP Version: 7.1.11

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

-- --------------------------------------------------------

--
-- テーブルの構造 `allocation`
--

CREATE TABLE `allocation` (
  `allocationID` int(11) NOT NULL,
  `applicationID` int(11) NOT NULL,
  `unitNo` int(11) NOT NULL,
  `formDate` date NOT NULL,
  `duration` int(11) NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `allocation`
--

INSERT INTO `allocation` (`allocationID`, `applicationID`, `unitNo`, `formDate`, `duration`, `endDate`) VALUES
(1, 1, 3, '2020-03-18', 12, '2021-03-18');

-- --------------------------------------------------------

--
-- テーブルの構造 `applicant`
--

CREATE TABLE `applicant` (
  `applicantID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `monthlyIncome` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `applicant`
--

INSERT INTO `applicant` (`applicantID`, `userID`, `email`, `monthlyIncome`) VALUES
(1, 1, 'caroldoe@example.com', 170),
(2, 3, 'thpotet@example.com', 150);

-- --------------------------------------------------------

--
-- テーブルの構造 `application`
--

CREATE TABLE `application` (
  `applicationID` int(11) NOT NULL,
  `applicantID` int(11) NOT NULL,
  `residenceID` int(11) NOT NULL,
  `applicationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `requiredMonth` int(11) NOT NULL,
  `requiredYear` int(11) NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `application`
--

INSERT INTO `application` (`applicationID`, `applicantID`, `residenceID`, `applicationDate`, `requiredMonth`, `requiredYear`, `status`) VALUES
(1, 1, 1, '2020-03-31 01:00:13', 1, 1, 'Approve');

-- --------------------------------------------------------

--
-- テーブルの構造 `housingofficer`
--

CREATE TABLE `housingofficer` (
  `staffID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `housingofficer`
--

INSERT INTO `housingofficer` (`staffID`, `userID`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `residence`
--

CREATE TABLE `residence` (
  `residenceID` int(11) NOT NULL,
  `staffID` int(11) NOT NULL,
  `address` varchar(200) NOT NULL,
  `numUnits` int(11) NOT NULL,
  `sizePerUnit` float NOT NULL,
  `monthlyRental` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `residence`
--

INSERT INTO `residence` (`residenceID`, `staffID`, `address`, `numUnits`, `sizePerUnit`, `monthlyRental`) VALUES
(1, 1, 'Jl Boulevard XXI', 5, 2, 5);

-- --------------------------------------------------------

--
-- テーブルの構造 `unit`
--

CREATE TABLE `unit` (
  `unitNo` int(11) NOT NULL,
  `residenceID` int(11) NOT NULL,
  `availability` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `unit`
--

INSERT INTO `unit` (`unitNo`, `residenceID`, `availability`) VALUES
(3, 1, 'used'),
(4, 1, 'available'),
(5, 1, 'available'),
(6, 1, 'used'),
(7, 1, 'available');

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(256) NOT NULL,
  `fullname` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `fullname`) VALUES
(1, 'usertest', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'Carol (User)'),
(2, 'admintest', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'Suliw (Admin)'),
(3, 'mitchgbn', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'Mitch Gibson');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allocation`
--
ALTER TABLE `allocation`
  ADD PRIMARY KEY (`allocationID`),
  ADD KEY `applicationID` (`applicationID`),
  ADD KEY `unitNo` (`unitNo`);

--
-- Indexes for table `applicant`
--
ALTER TABLE `applicant`
  ADD PRIMARY KEY (`applicantID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`applicationID`),
  ADD KEY `applicantID` (`applicantID`),
  ADD KEY `residenceID` (`residenceID`);

--
-- Indexes for table `housingofficer`
--
ALTER TABLE `housingofficer`
  ADD PRIMARY KEY (`staffID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `residence`
--
ALTER TABLE `residence`
  ADD PRIMARY KEY (`residenceID`),
  ADD KEY `staffID` (`staffID`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unitNo`),
  ADD KEY `residenceID` (`residenceID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allocation`
--
ALTER TABLE `allocation`
  MODIFY `allocationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applicant`
--
ALTER TABLE `applicant`
  MODIFY `applicantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
  MODIFY `applicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `housingofficer`
--
ALTER TABLE `housingofficer`
  MODIFY `staffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `residence`
--
ALTER TABLE `residence`
  MODIFY `residenceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unitNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `allocation`
--
ALTER TABLE `allocation`
  ADD CONSTRAINT `allocation_ibfk_1` FOREIGN KEY (`applicationID`) REFERENCES `application` (`applicationID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `allocation_ibfk_2` FOREIGN KEY (`unitNo`) REFERENCES `unit` (`unitNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `applicant`
--
ALTER TABLE `applicant`
  ADD CONSTRAINT `applicant_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`applicantID`) REFERENCES `applicant` (`applicantID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`residenceID`) REFERENCES `residence` (`residenceID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `housingofficer`
--
ALTER TABLE `housingofficer`
  ADD CONSTRAINT `housingofficer_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `residence`
--
ALTER TABLE `residence`
  ADD CONSTRAINT `residence_ibfk_1` FOREIGN KEY (`staffID`) REFERENCES `housingofficer` (`staffID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `unit`
--
ALTER TABLE `unit`
  ADD CONSTRAINT `unit_ibfk_1` FOREIGN KEY (`residenceID`) REFERENCES `residence` (`residenceID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
