
CREATE TABLE `pre_device` (
    `id` mediumint(8) unsigned NOT NULL auto_increment,
    `name` varchar(50) NOT NULL,
    `model` varchar(50) DEFAULT NULL,
    `location` varchar(255) DEFAULT NULL,
    `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
    `admin` varchar(50) DEFAULT NULL,
    `kindly_reminder` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) Engine=MyISAM  DEFAULT CHARSET=utf8;
