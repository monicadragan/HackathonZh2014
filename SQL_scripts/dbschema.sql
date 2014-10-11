-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Ott 11, 2014 alle 05:09
-- Versione del server: 5.5.33
-- Versione PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `hackzurich`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `b1978_user_profiles`
--

CREATE TABLE `b1978_user_profiles` (
  `user_id` int(11) NOT NULL,
  `profile_key` varchar(100) NOT NULL,
  `profile_value` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `idx_user_id_profile_key` (`user_id`,`profile_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Simple user profile storage table';

--
-- Dump dei dati per la tabella `b1978_user_profiles`
--

INSERT INTO `b1978_user_profiles` (`user_id`, `profile_key`, `profile_value`, `ordering`) VALUES
(555, 'profile.aboutme', '""', 10),
(555, 'profile.address1', '""', 1),
(555, 'profile.address2', '""', 2),
(555, 'profile.city', '""', 3),
(555, 'profile.country', '""', 5),
(555, 'profile.dob', '""', 11),
(555, 'profile.favoritebook', '""', 9),
(555, 'profile.phone', '""', 7),
(555, 'profile.postal_code', '""', 6),
(555, 'profile.region', '""', 4),
(555, 'profile.website', 'false', 8),
(555, 'testprofile.organization', 'This is a custom field', 1),
(556, 'profile.aboutme', '""', 10),
(556, 'profile.address1', '""', 1),
(556, 'profile.address2', '""', 2),
(556, 'profile.city', '""', 3),
(556, 'profile.country', '""', 5),
(556, 'profile.dob', '""', 11),
(556, 'profile.favoritebook', '""', 9),
(556, 'profile.phone', '""', 7),
(556, 'profile.postal_code', '""', 6),
(556, 'profile.region', '""', 4),
(556, 'profile.website', 'false', 8),
(556, 'testprofile.organization', 'unige', 1),
(558, 'testprofile.degree program', 'cs', 2),
(558, 'testprofile.Fonctionalanalysis', '1', 4),
(558, 'testprofile.Human-computerinteraction', '1', 9),
(558, 'testprofile.Introductionstoobjectsorientedprogramming', '1', 10),
(558, 'testprofile.Linearmodels', '1', 5),
(558, 'testprofile.Mathematicallogic', '1', 6),
(558, 'testprofile.Probabilitiesandstatistics', '1', 8),
(558, 'testprofile.Timeseries', '1', 7),
(558, 'testprofile.Topology', '1', 3),
(558, 'testprofile.University', 'unibo', 1);
