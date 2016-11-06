
CREATE TABLE `pre_device` (
    `id` mediumint(8) unsigned NOT NULL auto_increment,
    `name` varchar(50) NOT NULL,
    `model` varchar(50) DEFAULT NULL,
    `location` varchar(255) DEFAULT NULL,
    `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
    `adminid` mediumint(8) NOT NULL,
    `kindly_reminder` text DEFAULT NULL,
    `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `deleted` (`deleted`)
) Engine=MyISAM  DEFAULT CHARSET=utf8;
