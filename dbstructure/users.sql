DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `group_id` tinyint(4) NOT NULL,
  `group_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `groups` (`group_id`, `group_name`) VALUES
(1, 'Administrator'),
(3, 'Member'),
(2, 'Moderator');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` char(64) NOT NULL,
  `session_key` char(32) DEFAULT NULL,
  `group` tinyint(4) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `mail` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `group_name` (`group_name`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `FK_users_groups` (`group`);

ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_groups` FOREIGN KEY (`group`) REFERENCES `groups` (`group_id`);