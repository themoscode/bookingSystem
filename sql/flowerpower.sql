-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2014 at 06:57 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `flowerpower`
--
CREATE DATABASE IF NOT EXISTS `flowerpower` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `flowerpower`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=357 ;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`ID`, `username`, `password`) VALUES
(2, 'Atnan', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(356, 'admin', '9ca4fdf1cef71bcf8afc70b0f8013d5b5acd4cb7');

-- --------------------------------------------------------

--
-- Table structure for table `basket`
--

CREATE TABLE IF NOT EXISTS `basket` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `sessionID` varchar(255) NOT NULL,
  `routeStationDetailsID` int(11) NOT NULL,
  `adults` int(11) NOT NULL,
  `price_total_adults` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `price_total_children` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `stations` varchar(50) NOT NULL,
  `departure` varchar(50) NOT NULL,
  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `basket`
--

INSERT INTO `basket` (`ID`, `sessionID`, `routeStationDetailsID`, `adults`, `price_total_adults`, `children`, `price_total_children`, `price`, `stations`, `departure`, `addTime`) VALUES
(3, 'efqllu4g69k7tic4t1jasrtk36', 1, 1, 20, 0, 0, 20, 'Berlin ZOB → Frankfurt Hbf', 'Mi.01.Oktober,10:00', '2014-08-22 16:53:54'),
(4, 'efqllu4g69k7tic4t1jasrtk36', 6, 1, 20, 0, 0, 20, 'Frankfurt Hbf → Berlin ZOB', 'Mi.08.Oktober,14:15', '2014-08-22 16:53:54');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `sessionID` varchar(255) NOT NULL,
  `bookTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `book_userID` int(11) NOT NULL,
  `routeStationDetailsID` int(11) NOT NULL,
  `returnFlag` tinyint(4) NOT NULL DEFAULT '0',
  `adultPassengers` int(11) NOT NULL,
  `childPassengers` int(11) NOT NULL,
  `bicycles` int(11) NOT NULL,
  `priceTotal` decimal(10,0) NOT NULL,
  `bookStatus` tinyint(4) NOT NULL DEFAULT '1',
  `bookAdmin` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`ID`, `sessionID`, `bookTime`, `book_userID`, `routeStationDetailsID`, `returnFlag`, `adultPassengers`, `childPassengers`, `bicycles`, `priceTotal`, `bookStatus`, `bookAdmin`) VALUES
(1, '', '2014-08-22 16:45:10', 1, 1, 0, 1, 1, 0, '36', 1, 2),
(2, '', '2014-08-22 16:45:12', 1, 6, 0, 1, 1, 0, '36', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `book_passenger`
--

CREATE TABLE IF NOT EXISTS `book_passenger` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `bookingID` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `mobileNumber` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `book_passenger`
--

INSERT INTO `book_passenger` (`ID`, `name`, `surname`, `type`, `bookingID`, `price`, `mobileNumber`) VALUES
(1, 'aaa', 'bb', 1, 1, '20', '12345'),
(2, 'ccc', 'ddd', 2, 1, '16', ''),
(3, 'aaa', 'bbb', 1, 2, '20', '12345'),
(4, 'ccc', 'ddd', 2, 2, '16', '');

-- --------------------------------------------------------

--
-- Table structure for table `book_user`
--

CREATE TABLE IF NOT EXISTS `book_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `surname` varchar(20) NOT NULL DEFAULT '',
  `sex` text NOT NULL,
  `form` text NOT NULL,
  `mobileNumber` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(20) NOT NULL DEFAULT '',
  `street` text NOT NULL,
  `streetNumber` int(11) NOT NULL,
  `city` text NOT NULL,
  `postCode` int(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `book_user`
--

INSERT INTO `book_user` (`ID`, `name`, `surname`, `sex`, `form`, `mobileNumber`, `email`, `street`, `streetNumber`, `city`, `postCode`) VALUES
(1, 'eee', 'fff', 'Frau', 'Person', '77777', 'themos.kost@yahoo.gr', 'gggg', 12, 'jjjjj', 1123);

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE IF NOT EXISTS `bus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `busModelID` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `notes` varchar(500) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`ID`, `busModelID`, `code`, `notes`) VALUES
(1, 1, '13552285', '');

-- --------------------------------------------------------

--
-- Table structure for table `busmodel`
--

CREATE TABLE IF NOT EXISTS `busmodel` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(20) NOT NULL DEFAULT '',
  `numberOfSeats` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `busmodel`
--

INSERT INTO `busmodel` (`ID`, `model`, `numberOfSeats`) VALUES
(1, 'Peugeot Boxer', 30);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `theName` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`ID`, `name`) VALUES
(1, 'Berlin'),
(2, 'Frankfurt'),
(3, 'Wiesbaden');

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE IF NOT EXISTS `route` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `numberOfStations` int(11) NOT NULL DEFAULT '2',
  `firstStationID` int(11) NOT NULL DEFAULT '1',
  `lastStationID` int(11) NOT NULL DEFAULT '1',
  `stationsIDs` varchar(200) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `route_id` (`ID`,`name`),
  UNIQUE KEY `StationsIDs` (`stationsIDs`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`ID`, `numberOfStations`, `firstStationID`, `lastStationID`, `stationsIDs`, `name`) VALUES
(1, 3, 1, 3, '1,2,3,', 'Berlin ZOB - Wiesbaden Hbf'),
(2, 3, 3, 1, '3,2,1,', 'Wiesbaden Hbf - Berlin ZOB');

-- --------------------------------------------------------

--
-- Table structure for table `route_station_details`
--

CREATE TABLE IF NOT EXISTS `route_station_details` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `routeID` int(11) NOT NULL,
  `uniqueRouteID` int(11) NOT NULL,
  `stationFromID` int(11) NOT NULL,
  `stationToID` int(11) NOT NULL,
  `price` float NOT NULL,
  `departure` datetime NOT NULL,
  `arrival` datetime NOT NULL,
  `reservedSeats` int(11) NOT NULL,
  `freeSeats` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `route_station_details`
--

INSERT INTO `route_station_details` (`ID`, `routeID`, `uniqueRouteID`, `stationFromID`, `stationToID`, `price`, `departure`, `arrival`, `reservedSeats`, `freeSeats`) VALUES
(1, 1, 1, 1, 2, 20, '2014-10-01 10:00:00', '2014-10-01 14:00:00', 0, 27),
(2, 1, 1, 1, 3, 30, '2014-10-01 10:00:00', '2014-10-01 16:00:00', 0, 27),
(3, 1, 1, 2, 3, 20, '2014-10-01 14:15:00', '2014-10-01 16:00:00', 0, 30),
(4, 2, 2, 3, 2, 20, '2014-10-08 10:00:00', '2014-10-08 14:00:00', 0, 30),
(5, 2, 2, 3, 1, 30, '2014-10-08 10:00:00', '2014-10-08 16:00:00', 0, 27),
(6, 2, 2, 2, 1, 20, '2014-10-08 14:15:00', '2014-10-08 16:00:00', 0, 27);

-- --------------------------------------------------------

--
-- Table structure for table `route_station_order`
--

CREATE TABLE IF NOT EXISTS `route_station_order` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `routeID` int(11) NOT NULL,
  `stationID` int(11) NOT NULL,
  `stationOrder` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `station_route_id` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `route_station_order`
--

INSERT INTO `route_station_order` (`ID`, `routeID`, `stationID`, `stationOrder`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3),
(4, 2, 3, 1),
(5, 2, 2, 2),
(6, 2, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `station`
--

CREATE TABLE IF NOT EXISTS `station` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `cityID` int(11) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`ID`, `cityID`, `name`) VALUES
(1, 1, 'Berlin ZOB'),
(2, 2, 'Frankfurt Hbf'),
(3, 3, 'Wiesbaden Hbf');

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `counter` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `counter`, `date`, `url`) VALUES
(1, 1, '2014-08-16 00:00:00', 'www.test.gr');

-- --------------------------------------------------------

--
-- Table structure for table `unique_route`
--

CREATE TABLE IF NOT EXISTS `unique_route` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `routeID` int(11) NOT NULL,
  `busID` int(11) NOT NULL,
  `departure` datetime NOT NULL,
  `arrival` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `unique_route`
--

INSERT INTO `unique_route` (`ID`, `routeID`, `busID`, `departure`, `arrival`, `active`) VALUES
(1, 1, 1, '2014-08-23 00:00:00', '2014-08-23 00:00:00', 1),
(2, 2, 1, '2014-08-24 00:00:00', '2014-08-24 00:00:00', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
