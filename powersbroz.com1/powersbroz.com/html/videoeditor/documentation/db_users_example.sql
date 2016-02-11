CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `email` varchar(64) DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `new_password` varchar(128) DEFAULT '',
  `type` varchar(64) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;