
CREATE TABLE `pre_reservation` (
    `id` mediumint(8) unsigned NOT NULL auto_increment,
    `userid` mediumint(8) unsigned NOT NULL,
    `deviceid` mediumint(8) unsigned NOT NULL,
    `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
    `time_start` int(11) unsigned NOT NULL,
    `time_end` int(11) unsigned NOT NULL,
    `sample_number` smallint(5) unsigned NOT NULL,
    `sample_description` text NOT NULL,
    `comment` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `userid` (`userid`),
    KEY `timespan` (`deviceid`, `time_start`, `time_end`)
) Engine=MyISAM  DEFAULT CHARSET=utf8;
