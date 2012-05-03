-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-04-2012 a las 21:00:35
-- Versión del servidor: 5.5.22
-- Versión de PHP: 5.3.10-1ubuntu3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `itd_dev`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audiences`
--

DROP TABLE IF EXISTS `audiences`;
CREATE TABLE IF NOT EXISTS `audiences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `audience` varchar(35) NOT NULL,
  `audience_name_en` varchar(35) NOT NULL,
  `normalized_name_en` varchar(60) NOT NULL,
  `audience_name_es` varchar(35) NOT NULL,
  `normalized_name_es` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `audiences`
--

INSERT INTO `audiences` (`id`, `audience`, `audience_name_en`, `normalized_name_en`, `audience_name_es`, `normalized_name_es`) VALUES
(1, 'all', 'All Audiences', 'all-audiences', 'Todos los públicos', 'todos-los-publicos'),
(2, 'kids', 'Kids', 'kids', 'Infantil', 'infantil'),
(3, 'adults', 'Adults', 'adults', 'Adultos', 'adultos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name_en` varchar(15) NOT NULL,
  `normalized_name_en` varchar(60) NOT NULL,
  `cat_name_es` varchar(15) NOT NULL,
  `normalized_name_es` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `country` varchar(3) NOT NULL,
  `company_name` varchar(125) NOT NULL,
  `normalized_name` varchar(60) NOT NULL,
  `image` varchar(255) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `founded` int(10) unsigned NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(65) NOT NULL,
  `state` varchar(60) NOT NULL,
  `spanish_community` varchar(22) DEFAULT NULL,
  `postal_code` varchar(12) NOT NULL,
  `phone` varchar(18) NOT NULL,
  `mobile` varchar(18) NOT NULL,
  `email` varchar(65) NOT NULL,
  `website` varchar(90) NOT NULL,
  `members` int(10) unsigned NOT NULL,
  `contact_person` varchar(60) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `inthedir` tinyint(3) unsigned NOT NULL,
  `status` varchar(10) NOT NULL,
  `auth` tinyint(3) unsigned NOT NULL,
  `counter` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id_idx` (`category_id`),
  KEY `country_idx` (`country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=983 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_country` varchar(2) NOT NULL,
  `name_es` varchar(20) NOT NULL,
  `name_en` varchar(20) NOT NULL,
  `iso3` varchar(3) NOT NULL,
  `isonum` varchar(3) NOT NULL,
  `phone_prefix` varchar(10) DEFAULT NULL,
  `country_group` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_country` (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `meta`
--

DROP TABLE IF EXISTS `meta`;
CREATE TABLE IF NOT EXISTS `meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(35) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_image` varchar(50) DEFAULT NULL,
  `company_id` int(10) unsigned DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `fb_userid` int(11) DEFAULT NULL,
  `trial` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `entity_id` (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provinces_es`
--

DROP TABLE IF EXISTS `provinces_es`;
CREATE TABLE IF NOT EXISTS `provinces_es` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province` varchar(125) DEFAULT NULL,
  `comunidad_autonoma` varchar(125) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `searches`
--

DROP TABLE IF EXISTS `searches`;
CREATE TABLE IF NOT EXISTS `searches` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` int(11) NOT NULL,
  `text_search` text NOT NULL,
  `title` varchar(125) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `image` varchar(50) NOT NULL,
  `normalized_name` varchar(60) NOT NULL,
  `source` varchar(20) NOT NULL,
  `itd` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `source_id` (`source_id`),
  FULLTEXT KEY `text_search_2` (`text_search`),
  FULLTEXT KEY `title_2` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1132 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `spectacles`
--

DROP TABLE IF EXISTS `spectacles`;
CREATE TABLE IF NOT EXISTS `spectacles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL,
  `audience_id` int(10) unsigned NOT NULL,
  `spectacle_name` varchar(90) NOT NULL,
  `normalized_name` varchar(60) NOT NULL,
  `premiere` int(10) unsigned NOT NULL,
  `short_description` text NOT NULL,
  `sinopsis` text NOT NULL,
  `director` varchar(50) NOT NULL,
  `length` int(10) unsigned NOT NULL,
  `ages_from` int(10) unsigned DEFAULT NULL,
  `ages_to` int(10) unsigned DEFAULT NULL,
  `credit_titles` text NOT NULL,
  `sheet` text NOT NULL,
  `inthedir` tinyint(3) unsigned NOT NULL,
  `auth` tinyint(3) unsigned NOT NULL,
  `status` varchar(10) NOT NULL,
  `counter` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id_idx` (`company_id`),
  KEY `audience_id_idx` (`audience_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `spectacles_categories`
--

DROP TABLE IF EXISTS `spectacles_categories`;
CREATE TABLE IF NOT EXISTS `spectacles_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spectacle_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `spectacle_id_idx` (`spectacle_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=104 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `spectacles_media`
--

DROP TABLE IF EXISTS `spectacles_media`;
CREATE TABLE IF NOT EXISTS `spectacles_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spectacle_id` int(10) unsigned NOT NULL,
  `media_type` varchar(12) NOT NULL,
  `media` varchar(125) NOT NULL,
  `main` tinyint(1) NOT NULL,
  `description_en` varchar(255) DEFAULT NULL,
  `description_es` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `spectacle_id_idx` (`spectacle_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=401 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(40) DEFAULT NULL,
  `salt` varchar(40) NOT NULL,
  `email` varchar(65) DEFAULT NULL,
  `activation_code` varchar(40) NOT NULL,
  `forgotten_password_code` varchar(40) NOT NULL,
  `remember_code` varchar(40) NOT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `active` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id_idx` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=140 ;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `companies_ibfk_2` FOREIGN KEY (`country`) REFERENCES `countries` (`id_country`);

--
-- Filtros para la tabla `spectacles`
--
ALTER TABLE `spectacles`
  ADD CONSTRAINT `spectacles_audience_id_audiences_id` FOREIGN KEY (`audience_id`) REFERENCES `audiences` (`id`),
  ADD CONSTRAINT `spectacles_company_id_companies_id` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);
