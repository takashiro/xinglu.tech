
CREATE TABLE `pre_device` (
    `id` mediumint(8) unsigned NOT NULL auto_increment,
    `name` varchar(50) NOT NULL,
    `type` varchar(50) NOT NULL,
    `location` varchar(255) NOT NULL,
    `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
    `admin` varchar(50) NOT NULL,
    `kindly_reminder` text NOT NULL,
    PRIMARY KEY (`id`)
) Engine=MyISAM  DEFAULT CHARSET=utf8;
