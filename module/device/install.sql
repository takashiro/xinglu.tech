
CREATE TABLE `pre_device` (
    `id` mediumint(8) unsigned NOT NULL auto_increment,
    `name` varchar(50) NOT NULL DEFAULT '',
    `type` varchar(50) NOT NULL DEFAULT '',
    `location` varchar(255) NOT NULL DEFAULT '',
    `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
    `admin` varchar(50) NOT NULL DEFAULT '',
    `kindly_reminder` text NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) Engine=MyISAM  DEFAULT CHARSET=utf8;
