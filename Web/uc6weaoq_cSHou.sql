-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: mysqlhost
-- Creato il: Apr 27, 2016 alle 14:43
-- Versione del server: 10.0.19-MariaDB-log
-- Versione PHP: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uc6weaoq_cSHou`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `locationID` tinyint(4) NOT NULL,
  `event` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `data`
--

INSERT INTO `data` (`timestamp`, `locationID`, `event`) VALUES
('2016-04-25 11:17:13', 1, 1),
('2016-04-25 11:17:14', 1, -1),
('2016-04-25 11:17:19', 1, 1),
('2016-04-25 11:17:22', 1, 1),
('2016-04-25 11:21:22', 1, -1),
('2016-04-25 11:22:47', 1, 1),
('2016-04-25 11:22:49', 1, -1),
('2016-04-25 11:22:53', 1, -1),
('2016-04-25 11:25:12', 1, 1),
('2016-04-25 11:25:12', 1, -1),
('2016-04-25 11:25:14', 1, 2),
('2016-04-25 11:25:16', 1, 2),
('2016-04-25 11:25:16', 1, -1),
('2016-04-25 15:58:24', 1, 1),
('2016-04-25 15:59:30', 1, 1),
('2016-04-25 15:59:30', 1, 2),
('2016-04-25 15:59:30', 1, 1),
('2016-04-25 15:59:30', 1, 2),
('2016-04-25 15:59:30', 1, -1),
('2016-04-25 15:59:30', 1, -1),
('2016-04-25 15:59:30', 1, -2),
('2016-04-25 15:59:30', 1, 1),
('2016-04-25 18:01:31', 1, 2),
('2016-04-25 18:03:02', 1, 3),
('2016-04-26 13:27:43', 1, 2),
('2016-04-26 13:29:56', 1, 3),
('2016-04-26 13:30:06', 1, -1),
('2016-04-26 13:30:14', 1, 1),
('2016-04-26 13:30:21', 1, -2),
('2016-04-26 13:30:27', 1, 3),
('2016-04-26 13:30:40', 1, 1),
('2016-04-26 13:33:48', 1, -1),
('2016-04-26 15:01:26', 1, 3),
('2016-04-26 15:01:36', 1, -1),
('2016-04-26 15:01:43', 1, 1),
('2016-04-26 15:40:27', 1, 3),
('2016-04-26 15:45:25', 1, 3),
('2016-04-26 15:45:33', 1, 5),
('2016-04-26 16:15:43', 1, 1),
('2016-04-26 17:04:32', 1, 3),
('2016-04-26 17:07:22', 1, 3),
('2016-04-26 17:07:27', 1, 3),
('2016-04-26 17:17:25', 1, 5),
('2016-04-26 17:19:33', 1, 1),
('2016-04-26 17:20:16', 1, -3),
('2016-04-26 17:22:57', 1, -5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
