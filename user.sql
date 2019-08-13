CREATE TABLE `user` (
  `github_id` int(11) UNSIGNED NOT NULL,
  `github_login` varchar(255) NOT NULL,
  PRIMARY KEY (github_id)
) ENGINE=InnoDB;