
CREATE TABLE `pre_reservation` (
    `id` mediumint(8) unsigned NOT NULL auto_increment,
    `deviceid` mediumint(8) unsigned NOT NULL,
    `time_start` int(11) unsigned NOT NULL,
    `time_end` int(11) unsigned NOT NULL,
    `sample_number` smallint(5) unsigned NOT NULL,
    `sample_description` text NOT NULL,
    `comment` text NOT NULL,
    PRIMARY KEY (`id`)
) Engine=MyISAM  DEFAULT CHARSET=utf8;