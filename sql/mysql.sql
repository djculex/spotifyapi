CREATE TABLE `spotifyapi_music` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `times` text NOT NULL,
  `image` text NOT NULL,
  `artist` text NOT NULL,
  `title`  text NOT NULL,
  `album`  text NOT NULL,
  `releaseyear` int(4) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;