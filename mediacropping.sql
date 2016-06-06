CREATE TABLE `media_cropping` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `meta` text NOT NULL,
  `path` text NOT NULL,
  `entity_type` varchar(255) NOT NULL,
  `entity` bigint(20) NOT NULL,
  `size_key` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_pair` (`media_id`,`entity_type`,`entity`,`size_key`),
  CONSTRAINT `media_fk` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;