CREATE TABLE `#__quipu_sequence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  `next_value` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`next_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;