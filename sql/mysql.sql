CREATE TABLE `spotifyapi_music` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `times` text NOT NULL,
  `image` text NOT NULL,
  `artist` text NOT NULL,
  `title` text NOT NULL,
  `album` text NOT NULL,
  `releaseyear` int(4) DEFAULT NULL,
  `artistlink` text DEFAULT NULL,
  `playlistlink` text DEFAULT NULL,
  `popularity` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;