-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Време на генериране: 
-- Версия на сървъра: 5.5.32
-- Версия на PHP: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- БД: `framework`
--
CREATE DATABASE IF NOT EXISTS `framework` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `framework`;

-- --------------------------------------------------------

--
-- Структура на таблица `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `from` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from` (`from`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Схема на данните от таблица `article`
--

INSERT INTO `article` (`id`, `title`, `content`, `from`) VALUES
(1, 'Technicolor And Dreamworks Animation Joint Venture M-Go Brings Its Digital Movie Storefront To LG Smart TVs', 'Online movie storefront M-Go wants to be all the devices and screens where viewers plan to watch movies. To that end, it just struck a deal to get its video app embedded on connected TVs from LG, and is working to provide a more personalized experience for users.', ''),
(3, 'Second Article', 'dsa', 'Anonymous');

-- --------------------------------------------------------

--
-- Структура на таблица `form_validations`
--

CREATE TABLE IF NOT EXISTS `form_validations` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Схема на данните от таблица `form_validations`
--

INSERT INTO `form_validations` (`id`, `address1`, `address2`, `name`) VALUES
(1, 'Article/new', 'Article/create', 'Create new Article'),
(2, 'Article/edit', 'Article/update', 'Edit Article');

-- --------------------------------------------------------

--
-- Структура на таблица `infopage`
--

CREATE TABLE IF NOT EXISTS `infopage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Схема на данните от таблица `infopage`
--

INSERT INTO `infopage` (`id`, `created`, `updated`, `title`, `content`, `status`, `seo_description`, `seo_keywords`) VALUES
(1, 1378387211, 1378390745, 'Info Page''s Title', '<p> This is page content </p>\r\n<h5> And this shall be a title </h5>', 1, 'dddd', ''),
(2, 1378387700, 1378389884, 'Second info page', 'this is second info page !!!aaaa', 1, 'ds, dsaaaa', 'ddddd');

-- --------------------------------------------------------

--
-- Структура на таблица `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Схема на данните от таблица `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES
(4, 'svetlio', 'svetlio@mail.bg', '8bad263b3adedab3f09966d2e86da981');

-- --------------------------------------------------------

--
-- Структура на таблица `validations`
--

CREATE TABLE IF NOT EXISTS `validations` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `relation_id` int(7) NOT NULL,
  `field` varchar(100) NOT NULL,
  `rule` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `field` (`field`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Схема на данните от таблица `validations`
--

INSERT INTO `validations` (`id`, `relation_id`, `field`, `rule`, `value`) VALUES
(2, 1, 'title', 'required', '1'),
(3, 1, 'content', 'required', '1'),
(4, 1, 'title', 'testChars', ' '),
(5, 1, 'content', 'testChars', ' -.&rsquo;,'),
(7, 2, 'title', 'required', '1'),
(8, 2, 'content', 'required', '1'),
(9, 2, 'content', 'testChars', ' -.&rsquo;,'),
(10, 2, 'title', 'testChars', ' ');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
