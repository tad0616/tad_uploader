CREATE TABLE `tad_uploader` (
  `cat_sn` smallint(5) unsigned NOT NULL auto_increment,
  `cat_title` varchar(255) NOT NULL default '',
  `cat_desc` text NOT NULL,
  `cat_enable` enum('1','0') NOT NULL default '1',
  `uid` smallint(5) unsigned NOT NULL default '0',
  `of_cat_sn` smallint(5) unsigned NOT NULL default '0',
  `cat_share` enum('1','0') NOT NULL default '1',
  `cat_sort` smallint(6) NOT NULL default '0',
  `cat_count` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`cat_sn`),
  KEY `of_cat_sn` (`of_cat_sn`)
) ENGINE=MyISAM ;


CREATE TABLE `tad_uploader_file` (
  `cfsn` smallint(5) unsigned NOT NULL auto_increment,
  `cat_sn` smallint(5) unsigned NOT NULL default '0',
  `uid` smallint(5) unsigned NOT NULL default '0',
  `cf_name` varchar(255) NOT NULL default '',
  `cf_desc` text NOT NULL,
  `cf_type` varchar(255) NOT NULL default '',
  `cf_size` int(11) unsigned NOT NULL default '0',
  `cf_count` smallint(6) unsigned NOT NULL default '0',
  `up_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `file_url` varchar(255) NOT NULL default '',
  `cf_sort` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cfsn`),
  KEY `cat_sn` (`cat_sn`)
) ENGINE=MyISAM ;


CREATE TABLE `tad_uploader_dl_log` (
  `log_sn` smallint(5) unsigned NOT NULL auto_increment,
  `uid` smallint(5) unsigned NOT NULL default '0',
  `dl_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `from_ip` varchar(15) NOT NULL default '',
  `cfsn` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`log_sn`)
) ENGINE=MyISAM ;

CREATE TABLE `tad_uploader_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `col_name` varchar(255) NOT NULL default '',
  `col_sn` smallint(5) unsigned NOT NULL default '0',
  `sort` smallint(5) unsigned NOT NULL default '1',
  `kind` enum('img','file') NOT NULL default 'img',
  `file_name` varchar(255) NOT NULL default '',
  `file_type` varchar(255) NOT NULL default '',
  `file_size` int(10) unsigned NOT NULL default '0',
  `description` text NOT NULL,
  `counter` mediumint(8) unsigned NOT NULL default '0',
  `original_filename` varchar(255) NOT NULL default '',
  `hash_filename` varchar(255) NOT NULL default '',
  `sub_dir` varchar(255) NOT NULL default '',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM ;
