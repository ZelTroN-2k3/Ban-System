DROP TABLE IF EXISTS `bansystem_bans`;
DROP TABLE IF EXISTS `bansystem_bans-country`;
DROP TABLE IF EXISTS `bansystem_bans-other`;
DROP TABLE IF EXISTS `bansystem_bans-ranges`;
DROP TABLE IF EXISTS `bansystem_ip-whitelist`;
DROP TABLE IF EXISTS `bansystem_pages-layolt`;
DROP TABLE IF EXISTS `bansystem_settings`;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bansystem_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `ip` char(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` char(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bansystem_bans-country` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Banned countries table';

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bansystem_bans-other` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bansystem_bans-ranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `ip_range` char(19) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bansystem_ip-whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `ip` char(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bansystem_pages-layolt` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `page` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bansystem_pages-layolt` (`id`, `page`, `text`) VALUES
(1, 'Banned', 'You are banned and you cannot continue to the site'),
(2, 'Banned_Country', 'Sorry, but your country is banned and you cannot continue to the website'),
(3, 'Blocked_Browser', 'Access to the website through your Browser is not allowed, please use another Internet Browser'),
(4, 'Blocked_OS', 'Access to the website through your Operating System is not allowed'),
(5, 'Blocked_ISP', 'Your Internet Service Provider is blacklisted and you cannot continue to the website'),
(6, 'Blocked_RFR', 'Your referrer url is blocked and you cannot continue to the website');