-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 11, 2014 at 01:13 PM
-- Server version: 5.5.34
-- PHP Version: 5.3.10-1ubuntu3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `interq`
--

-- --------------------------------------------------------

--
-- Table structure for table `COLINDE`
--

CREATE TABLE IF NOT EXISTS `COLINDE` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(200) NOT NULL,
  `AUTHOR` varchar(1000) NOT NULL,
  `PLACE` varchar(100) NOT NULL,
  `YEAR` year(4) NOT NULL,
  `TEXT` varchar(5000) NOT NULL,
  `UPLOADED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `AUDIO_FILE` varchar(100) NOT NULL,
  `MUSIC_SHEET` varchar(100) NOT NULL,
  `COMPOSER` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DOMAINS`
--

CREATE TABLE IF NOT EXISTS `DOMAINS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `DOMAINS`
--

INSERT INTO `DOMAINS` (`ID`, `Name`) VALUES
(1, 'Computer science'),
(2, 'Mathematics'),
(3, 'Physics'),
(4, 'Biology');

-- --------------------------------------------------------

--
-- Table structure for table `EXAMS`
--

CREATE TABLE IF NOT EXISTS `EXAMS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `RESOURCES`
--

CREATE TABLE IF NOT EXISTS `RESOURCES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(1000) NOT NULL,
  `Link` varchar(1000) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `Fingerprint` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `RESOURCES`
--

INSERT INTO `RESOURCES` (`ID`, `Title`, `Link`, `AuthorId`, `Fingerprint`) VALUES
(1, 'Bernoulli distribution', 'http://www.statlect.com/Bernoulli_distribution.htm', 1, '110101'),
(2, 'Expectation-maximization algorithm', 'http://www.nature.com/nbt/journal/v26/n8/full/nbt1406.html', 1, '110101'),
(3, 'Visual receptive fields', 'http://www.sumanasinc.com/webcontent/animations/content/receptivefields.html', 2, '110101'),
(4, 'Bipolar Cells', 'https://www.youtube.com/watch?v=1D_nIIevdzc ', 2, '110101'),
(5, 'The Monty Hall Problem in Statistics', 'http://ed.ted.com/featured/PWb09pny', 3, '101001'),
(6, 'A neural portrait of the human mind', 'http://www.ted.com/talks/nancy_kanwisher_the_brain_is_a_swiss_army_knife', 2, '101010'),
(7, 'Normal distribution', 'http://www.statlect.com/ucdnrm1.htm', 1, '110101');

-- --------------------------------------------------------

--
-- Table structure for table `UNIVERSITIES`
--

CREATE TABLE IF NOT EXISTS `UNIVERSITIES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `UNIVERSITIES`
--

INSERT INTO `UNIVERSITIES` (`ID`, `Name`) VALUES
(1, 'EPFL'),
(2, 'ETHZ'),
(3, 'UPB'),
(4, 'UniBo');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
