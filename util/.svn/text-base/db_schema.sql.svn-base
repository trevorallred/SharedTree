-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 03, 2007 at 05:55 PM
-- Server version: 5.0.27
-- PHP Version: 5.2.0
-- 
-- Database: `sharedtree`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `app_announcement`
-- 

CREATE TABLE `app_announcement` (
  `announcement_id` int(10) unsigned NOT NULL auto_increment,
  `start_date` date NOT NULL,
  `stop_date` date default NULL,
  `user_id` bigint(20) NOT NULL,
  `announcement` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`announcement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_group`
-- 

CREATE TABLE `app_group` (
  `group_id` int(10) unsigned NOT NULL auto_increment,
  `group_name` varchar(255) collate latin1_general_ci NOT NULL,
  `creation_date` datetime default NULL,
  `created_by` bigint(20) default NULL,
  `update_date` datetime default NULL,
  `updated_by` bigint(20) default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `description` text collate latin1_general_ci,
  `start_year` int(11) default NULL,
  `end_year` int(11) default NULL,
  `member_count` int(10) unsigned NOT NULL default '0',
  `initials` char(2) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_import`
-- 

CREATE TABLE `app_import` (
  `import_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `current_step` tinyint(4) NOT NULL default '0',
  `filename` varchar(255) collate latin1_general_ci default NULL,
  `upload_date` datetime NOT NULL,
  `import_date` datetime default NULL,
  `description` varchar(255) collate latin1_general_ci default NULL,
  `file_size` int(10) unsigned NOT NULL default '0',
  `family_count` int(10) unsigned NOT NULL default '0',
  `person_count` int(10) unsigned NOT NULL default '0',
  `person_imported` int(10) unsigned NOT NULL default '0',
  `person_matched` int(10) unsigned NOT NULL default '0',
  `status_code` char(1) collate latin1_general_ci default 'P',
  `gedcom_text` mediumtext collate latin1_general_ci,
  PRIMARY KEY  (`import_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=126 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_log`
-- 

CREATE TABLE `app_log` (
  `user_id` bigint(20) unsigned NOT NULL default '0',
  `visit_date` datetime NOT NULL,
  `person_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_merge`
-- 

CREATE TABLE `app_merge` (
  `merge_id` bigint(20) unsigned NOT NULL auto_increment,
  `person_from_id` bigint(20) unsigned NOT NULL,
  `person_to_id` bigint(20) unsigned NOT NULL,
  `status_code` char(1) collate latin1_general_ci NOT NULL default 'P',
  `similarity_score` float unsigned NOT NULL default '0',
  `updated_by` bigint(20) unsigned default NULL,
  `update_date` datetime NOT NULL,
  `family_name` float NOT NULL default '0',
  `given_name` float NOT NULL default '0',
  `birthdate` float NOT NULL default '0',
  `birthplace` float NOT NULL default '0',
  `deathdate` float NOT NULL default '0',
  `deathplace` float NOT NULL default '0',
  `gender` float NOT NULL default '0',
  `parents` float NOT NULL default '0',
  `spouses` float NOT NULL default '0',
  PRIMARY KEY  (`merge_id`),
  UNIQUE KEY `person_from_id` (`person_from_id`,`person_to_id`),
  KEY `person_to_id` (`person_to_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4169 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_recent_view`
-- 

CREATE TABLE `app_recent_view` (
  `user_id` bigint(20) unsigned NOT NULL,
  `person_id` bigint(20) unsigned NOT NULL,
  `last_update_date` datetime NOT NULL,
  UNIQUE KEY `SECONDARY` (`user_id`,`person_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_referrer`
-- 

CREATE TABLE `app_referrer` (
  `referring_url` varchar(255) collate latin1_general_cs NOT NULL,
  `dest_url` varchar(255) collate latin1_general_cs NOT NULL,
  `ip_address` varchar(20) collate latin1_general_cs default NULL,
  `last_click_date` datetime NOT NULL,
  `clicks` int(11) NOT NULL default '1',
  UNIQUE KEY `referring_url` (`referring_url`,`dest_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_session`
-- 

CREATE TABLE `app_session` (
  `session_id` varchar(100) collate latin1_general_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `ses_start` bigint(20) NOT NULL,
  `ses_time` bigint(20) NOT NULL,
  `host_addr` varchar(20) collate latin1_general_ci default NULL,
  `session_text` text collate latin1_general_ci,
  PRIMARY KEY  (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_user`
-- 

CREATE TABLE `app_user` (
  `user_id` bigint(20) unsigned NOT NULL auto_increment,
  `email` varchar(100) collate latin1_general_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `update_date` datetime default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `password` varchar(200) collate latin1_general_ci default NULL,
  `password_new` varchar(10) collate latin1_general_ci default NULL,
  `given_name` varchar(100) collate latin1_general_ci default NULL,
  `family_name` varchar(100) collate latin1_general_ci default NULL,
  `person_id` bigint(20) default NULL,
  `permission_level` tinyint(3) NOT NULL default '0',
  `address_line1` varchar(100) collate latin1_general_ci default NULL,
  `address_line2` varchar(50) collate latin1_general_ci default NULL,
  `city` varchar(50) collate latin1_general_ci default NULL,
  `state_code` varchar(10) collate latin1_general_ci NOT NULL,
  `country_id` tinyint(4) NOT NULL,
  `postal_code` varchar(10) collate latin1_general_ci NOT NULL,
  `show_lds` int(11) NOT NULL,
  `last_visit_date` datetime default NULL,
  `visits` bigint(20) unsigned NOT NULL default '0',
  `line_update_date` datetime default NULL,
  `security_trust` smallint(6) NOT NULL default '0',
  `gen_trust` smallint(6) NOT NULL default '0',
  `description` text collate latin1_general_ci,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=359 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_user_email`
-- 

CREATE TABLE `app_user_email` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) character set latin1 NOT NULL,
  `update_date` datetime default NULL,
  `primary_flag` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `user_id` (`user_id`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_user_line_person`
-- 

CREATE TABLE `app_user_line_person` (
  `user_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `thru_id` bigint(20) NOT NULL,
  `trace` varchar(20) collate latin1_general_ci NOT NULL,
  `distance` bigint(20) NOT NULL default '99999999999',
  `view_flag` tinyint(3) unsigned NOT NULL default '1',
  UNIQUE KEY `user_id` (`user_id`,`person_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_user_login`
-- 

CREATE TABLE `app_user_login` (
  `login_id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` bigint(20) unsigned NOT NULL,
  `login_date` datetime NOT NULL,
  `ip_address` varchar(15) default NULL,
  `admin_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`login_id`),
  UNIQUE KEY `user_id` (`user_id`,`login_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_user_person`
-- 

CREATE TABLE `app_user_person` (
  `change_id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` bigint(20) unsigned NOT NULL,
  `person_id` bigint(20) unsigned NOT NULL,
  `change_request` datetime NOT NULL,
  PRIMARY KEY  (`change_id`),
  UNIQUE KEY `user_id` (`user_id`,`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_user_trust`
-- 

CREATE TABLE `app_user_trust` (
  `trust_id` bigint(20) unsigned NOT NULL auto_increment,
  `user1_id` bigint(20) unsigned NOT NULL,
  `user2_id` bigint(20) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `security_trust` tinyint(4) NOT NULL default '0',
  `gen_trust` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`trust_id`),
  UNIQUE KEY `user1_id` (`user1_id`,`user2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `app_watch`
-- 

CREATE TABLE `app_watch` (
  `watch_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `person_id` bigint(20) unsigned NOT NULL,
  `bookmark` tinyint(3) unsigned NOT NULL,
  `show_watch` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`watch_id`),
  UNIQUE KEY `user_id` (`user_id`,`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3074 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `discuss_post`
-- 

CREATE TABLE `discuss_post` (
  `post_id` bigint(20) unsigned NOT NULL auto_increment,
  `person_id` bigint(20) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `update_date` datetime NOT NULL,
  `updated_by` bigint(20) NOT NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `parent_id` bigint(20) unsigned default NULL,
  `post_text` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`post_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `discuss_wiki`
-- 

CREATE TABLE `discuss_wiki` (
  `wiki_id` bigint(20) NOT NULL auto_increment,
  `person_id` bigint(20) unsigned NOT NULL,
  `page_name` varchar(50) collate latin1_general_ci default NULL,
  `update_date` datetime NOT NULL,
  `updated_by` bigint(20) unsigned default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `wiki_text` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`wiki_id`),
  UNIQUE KEY `person_id` (`person_id`,`page_name`),
  KEY `update_date` (`update_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=17268 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `discuss_wiki_history`
-- 

CREATE TABLE `discuss_wiki_history` (
  `wiki_id` bigint(20) unsigned NOT NULL,
  `update_date` datetime NOT NULL,
  `updated_by` bigint(20) unsigned default NULL,
  `wiki_text` text collate latin1_general_ci NOT NULL,
  UNIQUE KEY `person_id` (`wiki_id`,`update_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_event`
-- 

CREATE TABLE `tree_event` (
  `event_id` bigint(20) unsigned NOT NULL auto_increment,
  `key_id` bigint(20) unsigned NOT NULL,
  `table_type` char(1) collate latin1_general_ci NOT NULL default 'P',
  `event_type` varchar(10) collate latin1_general_ci NOT NULL,
  `actual_start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `actual_end_date` datetime NOT NULL default '4000-01-01 00:00:00',
  `update_date` datetime NOT NULL,
  `updated_by` bigint(20) default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `event_date` date default NULL,
  `ad` tinyint(4) default '1',
  `date_approx` varchar(10) collate latin1_general_ci default '',
  `age_at_event` varchar(15) collate latin1_general_ci default '',
  `date_text` varchar(50) collate latin1_general_ci default NULL,
  `location` varchar(200) collate latin1_general_ci default NULL,
  `location_id` int(11) default NULL,
  `temple_code` varchar(10) collate latin1_general_ci default NULL,
  `status` varchar(10) collate latin1_general_ci default NULL,
  `notes` text collate latin1_general_ci,
  `source` varchar(200) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`event_id`,`actual_start_date`),
  UNIQUE KEY `SECONDARY` (`key_id`,`event_type`,`table_type`,`actual_start_date`),
  KEY `event_date` (`event_date`),
  KEY `update_date` (`updated_by`,`update_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=264985 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_family`
-- 

CREATE TABLE `tree_family` (
  `family_id` bigint(20) unsigned NOT NULL auto_increment,
  `creation_date` datetime NOT NULL,
  `created_by` bigint(20) default NULL,
  `actual_start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `actual_end_date` datetime NOT NULL default '4000-01-01 00:00:00',
  `update_date` datetime NOT NULL,
  `updated_by` bigint(20) default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `person1_id` bigint(20) unsigned default NULL,
  `person2_id` bigint(20) unsigned default NULL,
  `status_code` enum('M','S','D','U','N') collate latin1_general_ci NOT NULL default 'M',
  `notes` text collate latin1_general_ci NOT NULL,
  `descendant_count` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`family_id`,`actual_start_date`),
  UNIQUE KEY `SECONDARY` (`person1_id`,`person2_id`,`actual_start_date`),
  KEY `person2_id` (`person2_id`,`actual_end_date`),
  KEY `updated_by` (`updated_by`),
  KEY `updated_by_2` (`updated_by`,`update_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=42380 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_family_gedcom`
-- 

CREATE TABLE `tree_family_gedcom` (
  `family_id` bigint(20) unsigned NOT NULL,
  `import_id` int(10) unsigned NOT NULL,
  `family` varchar(10) NOT NULL,
  `gedcom_text` text NOT NULL,
  UNIQUE KEY `family_id` (`family_id`,`import_id`),
  KEY `import_id` (`import_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_image`
-- 

CREATE TABLE `tree_image` (
  `image_id` bigint(20) unsigned NOT NULL auto_increment,
  `creation_date` datetime NOT NULL,
  `created_by` bigint(20) default NULL,
  `update_date` datetime NOT NULL,
  `updated_by` bigint(20) default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `person_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned default NULL,
  `age_taken` tinyint(4) default NULL,
  `image_type` varchar(25) collate latin1_general_ci NOT NULL default 'jpeg',
  `image_size` bigint(20) NOT NULL default '0',
  `image_order` tinyint(4) NOT NULL default '1',
  `image_full` mediumblob NOT NULL,
  `image_thumb` blob NOT NULL,
  `image_med` blob NOT NULL,
  `image_name` varchar(50) collate latin1_general_ci default NULL,
  `description` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`image_id`),
  KEY `person_id` (`person_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=241 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_person`
-- 

CREATE TABLE `tree_person` (
  `person_id` bigint(20) unsigned NOT NULL auto_increment,
  `creation_date` datetime NOT NULL,
  `created_by` bigint(20) default NULL,
  `actual_start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `actual_end_date` datetime NOT NULL default '4000-01-01 00:00:00',
  `update_date` datetime NOT NULL,
  `updated_by` bigint(20) default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `gender` char(1) collate latin1_general_ci default NULL,
  `family_name` varchar(100) collate latin1_general_ci default NULL,
  `given_name` varchar(100) collate latin1_general_ci default NULL,
  `bio_family_id` bigint(20) default NULL,
  `adopt_family_id` bigint(20) default NULL,
  `child_order` tinyint(4) NOT NULL default '1',
  `merged_into` bigint(20) unsigned default NULL,
  `merge_rank` tinyint(4) NOT NULL default '0',
  `nickname` varchar(100) collate latin1_general_ci default NULL,
  `orig_family_name` varchar(100) collate latin1_general_ci default NULL,
  `orig_given_name` varchar(100) character set utf8 collate utf8_unicode_ci default NULL,
  `title` varchar(50) collate latin1_general_ci default NULL,
  `public_flag` tinyint(3) unsigned NOT NULL default '0',
  `birth_year` int(11) default NULL,
  `rin` varchar(20) collate latin1_general_ci default NULL,
  `afn` varchar(20) collate latin1_general_ci default NULL,
  `national_id` varchar(20) collate latin1_general_ci default NULL,
  `prefix` varchar(10) collate latin1_general_ci default NULL,
  `suffix` varchar(10) collate latin1_general_ci default NULL,
  `national_origin` varchar(20) collate latin1_general_ci default NULL,
  `occupation` varchar(50) collate latin1_general_ci default NULL,
  `descendant_count` bigint(20) NOT NULL default '0',
  `page_views` bigint(20) unsigned NOT NULL default '0',
  `temple_status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`actual_start_date`),
  KEY `bio_family_id` (`bio_family_id`),
  KEY `adopt_family_id` (`adopt_family_id`),
  KEY `merged_into` (`merged_into`),
  KEY `update_by_date` (`updated_by`,`update_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=150807 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_person_gedcom`
-- 

CREATE TABLE `tree_person_gedcom` (
  `person_id` bigint(20) unsigned NOT NULL,
  `import_id` int(10) unsigned NOT NULL,
  `individual` varchar(10) NOT NULL,
  `gedcom_text` text NOT NULL,
  UNIQUE KEY `person_id` (`person_id`,`import_id`),
  KEY `import_id` (`import_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_person_group`
-- 

CREATE TABLE `tree_person_group` (
  `person_group_id` bigint(20) unsigned NOT NULL auto_increment,
  `person_id` bigint(20) unsigned NOT NULL,
  `group_id` int(11) NOT NULL,
  `creation_date` datetime default NULL,
  `created_by` bigint(20) unsigned default '0',
  `update_date` datetime default NULL,
  `updated_by` bigint(20) unsigned default '0',
  `active_flag` tinyint(4) NOT NULL default '1',
  `notes` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`person_group_id`),
  UNIQUE KEY `person_id` (`person_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tree_residence`
-- 

CREATE TABLE `tree_residence` (
  `residence_id` bigint(20) unsigned NOT NULL auto_increment,
  `family_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned default NULL,
  `creation_date` datetime default NULL,
  `updated_by` bigint(20) default NULL,
  `update_date` datetime default NULL,
  `update_process` varchar(100) collate latin1_general_ci default NULL,
  `start_date` date default NULL,
  `end_date` date default NULL,
  `address_line1` varchar(100) collate latin1_general_ci default NULL,
  `address_line2` varchar(100) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`residence_id`),
  KEY `family_id` (`family_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Constraints for dumped tables
-- 

-- 
-- Constraints for table `tree_person_gedcom`
-- 
ALTER TABLE `tree_person_gedcom`
  ADD CONSTRAINT `tree_person_gedcom_ibfk_1` FOREIGN KEY (`import_id`) REFERENCES `app_import` (`import_id`);

-- 
-- Constraints for table `tree_residence`
-- 
ALTER TABLE `tree_residence`
  ADD CONSTRAINT `tree_residence_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `tree_family` (`family_id`) ON DELETE CASCADE;
-- Functions
-- 

CREATE DEFINER=`root`@`localhost` FUNCTION `merge_names`(n1 varchar(100), n2 varchar(100)) RETURNS varchar(200) CHARSET latin1
RETURN CONCAT(COALESCE(n1, ''),' ', COALESCE(n2, ''))

CREATE DEFINER=`root`@`localhost` FUNCTION `sp_dateformat`(dt_value datetime) RETURNS varchar(11) CHARSET latin1
RETURN IF(DATE_FORMAT(dt_value, '%d') <> '00', DATE_FORMAT(dt_value, '%e %b %Y'), IFNULL(DATE_FORMAT(dt_value, '%b %Y'), DATE_FORMAT(dt_value, '%Y') ))


-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 03, 2007 at 05:57 PM
-- Server version: 5.0.27
-- PHP Version: 5.2.0
-- 
-- Database: `sharedtree`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `ref_codes`
-- 

CREATE TABLE `ref_codes` (
  `ref_type` varchar(50) collate latin1_general_ci NOT NULL,
  `ref_code` char(2) collate latin1_general_ci NOT NULL,
  `meaning` varchar(255) collate latin1_general_ci default NULL,
  `seq` int(11) default NULL,
  UNIQUE KEY `ref_type` (`ref_type`,`ref_code`),
  KEY `meaning` (`meaning`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `ref_codes`
-- 

INSERT INTO `ref_codes` (`ref_type`, `ref_code`, `meaning`, `seq`) VALUES 
('Gender', 'F', 'Female', 2),
('Gender', 'M', 'Male', 1),
('LocationTypes', 'C', 'County', 4),
('LocationTypes', 'N', 'Country', 1),
('LocationTypes', 'P', 'Province', 3),
('LocationTypes', 'S', 'State', 2),
('LocationTypes', 'T', 'City or Town', 5);

-- --------------------------------------------------------

-- 
-- Table structure for table `ref_gedcom_codes`
-- 

CREATE TABLE `ref_gedcom_codes` (
  `gedcom_code` varchar(4) collate latin1_general_ci NOT NULL,
  `table_type` char(1) collate latin1_general_ci NOT NULL,
  `lds_flag` tinyint(4) NOT NULL default '0',
  `prompt` varchar(20) collate latin1_general_ci NOT NULL,
  `default_flag` tinyint(4) NOT NULL default '0',
  `seq` tinyint(4) NOT NULL default '99',
  `avg_age` smallint(5) unsigned default NULL,
  `description` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`gedcom_code`),
  UNIQUE KEY `prompt` (`prompt`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `ref_gedcom_codes`
-- 

INSERT INTO `ref_gedcom_codes` (`gedcom_code`, `table_type`, `lds_flag`, `prompt`, `default_flag`, `seq`, `avg_age`, `description`) VALUES 
('ADOP', 'P', 0, 'Adoption', 0, 99, 1, 'Pertaining to creation of a child-parent relations'),
('BAPL', 'P', 1, 'Baptism LDS', 1, 6, 8, 'The event of baptism performed at age eight or lat'),
('BAPM', 'P', 0, 'Baptism', 0, 99, 16, 'The event of baptism (not LDS), performed in infan'),
('BARM', 'P', 0, 'Bar Mitzvah', 0, 99, 13, 'The ceremonial event held when a Jewish boy reache'),
('BASM', 'P', 0, 'Bas Mitzvah', 0, 99, 13, 'The ceremonial event held when a Jewish girl reach'),
('BIRT', 'P', 0, 'Birth', 1, 1, 1, 'When an individual is born'),
('BLES', 'P', 0, 'Blessing', 0, 99, NULL, 'A religious event of bestowing divine care or inte'),
('BURI', 'P', 0, 'Burial', 0, 99, 65, 'The event of The proper disposing of The mortal re'),
('CENS', 'P', 0, 'Census', 0, 99, NULL, 'The event of the periodic count of the population '),
('CHR', 'P', 0, 'Christening', 0, 99, 0, 'The religious event (not LDS) of baptizing and/or '),
('CHRA', 'P', 0, 'Adult Christening', 0, 99, 35, 'The religious event (not LDS) of baptizing and/or '),
('CONF', 'P', 0, 'Confirmation', 0, 99, 15, 'The religious event (not LDS) of conferring the gi'),
('CONL', 'P', 1, 'Confirmation LDS', 0, 99, 8, 'The religious event by which a person receives mem'),
('CREM', 'P', 0, 'Cremation', 0, 99, 65, 'Disposal of the remains of a person''s body by fire'),
('DEAT', 'P', 0, 'Death', 1, 2, 65, 'When the person died'),
('DIV', 'F', 0, 'Divorce', 0, 99, 30, 'An event of dissolving a marriage through civil ac'),
('DIVF', 'F', 0, 'Divorce Filing', 0, 99, 30, 'An event of filing for a divorce by a spouse.'),
('EMIG', 'P', 0, 'Emigration', 0, 99, NULL, 'An event of leaving one''s homeland with the intent'),
('ENDL', 'P', 1, 'Endowment', 1, 7, 21, 'A religious event where an endowment ordinance for'),
('ENGA', 'F', 0, 'Engagement', 0, 99, 21, 'An event of recording or announcing an agreement b'),
('EVEN', 'P', 0, 'Event', 0, 99, NULL, 'A noteworthy happening related to an individual, a'),
('FCOM', 'P', 0, 'First Communion', 0, 99, 15, 'A religious rite, the first act of sharing in the '),
('GRAD', 'P', 0, 'Graduation', 0, 99, NULL, 'An event of awarding educational diplomas or degre'),
('IMMI', 'P', 0, 'Immigration', 0, 99, NULL, 'An event of entering into a new locality with the '),
('MARB', 'F', 0, 'Marriage Bann', 0, 99, NULL, 'An event of an official public notice given that t'),
('MARC', 'F', 0, 'Marriage Contract', 0, 99, NULL, 'An event of recording a formal agreement of marria'),
('MARL', 'F', 0, 'Marriage License', 0, 99, NULL, 'An event of obtaining a legal license to marry.'),
('MARR', 'F', 0, 'Marriage', 1, 1, 21, 'A legal, common-law, or customary event of creatin'),
('MARS', 'F', 0, 'Marriage Settlement', 0, 99, NULL, 'An event of creating an agreement between two peop'),
('NATU', 'P', 0, 'Naturalization', 0, 99, NULL, 'The event of obtaining citizenship.'),
('ORDN', 'P', 0, 'Ordination', 0, 99, NULL, 'A religious event of receiving authority to act in'),
('PROB', 'P', 0, 'Probate', 0, 99, NULL, 'An event of judicial determination of the validity'),
('RETI', 'P', 0, 'Retirement', 0, 99, NULL, 'An event of exiting an occupational relationship w'),
('SLGC', 'P', 1, 'Sealing Child', 1, 5, NULL, 'A religious event pertaining to the sealing of a c'),
('SLGS', 'F', 1, 'Sealing Spouse', 1, 5, NULL, 'A religious event pertaining to the sealing of a h'),
('WILL', 'P', 0, 'Will', 0, 99, NULL, 'A legal document treated as an event, by which a p');

-- --------------------------------------------------------

-- 
-- Table structure for table `ref_location`
-- 

CREATE TABLE `ref_location` (
  `location_id` int(10) unsigned NOT NULL auto_increment,
  `location_name` varchar(100) collate latin1_general_ci NOT NULL,
  `description` varchar(255) collate latin1_general_ci default NULL,
  `parent_id` int(10) unsigned NOT NULL default '0',
  `valid_start` mediumint(9) NOT NULL default '-4000',
  `valid_end` mediumint(9) NOT NULL default '4000',
  `location_type` char(1) collate latin1_general_ci default NULL,
  `display_name` varchar(255) collate latin1_general_ci default NULL,
  `modern_id` int(10) unsigned default NULL,
  `wikipedia` varchar(100) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`location_id`),
  UNIQUE KEY `location_name` (`location_name`,`parent_id`),
  KEY `parent_id` (`parent_id`),
  KEY `valid_dates` (`valid_start`,`valid_end`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=337 ;

-- 
-- Dumping data for table `ref_location`
-- 

INSERT INTO `ref_location` (`location_id`, `location_name`, `description`, `parent_id`, `valid_start`, `valid_end`, `location_type`, `display_name`, `modern_id`, `wikipedia`) VALUES 
(1, 'Mexico', '', 1, -4000, 4000, 'S', 'Mexico', NULL, NULL),
(2, 'USA', 'USA', 2, -4000, 4000, 'S', 'USA', NULL, NULL)

-- --------------------------------------------------------

-- 
-- Table structure for table `ref_location_spellings`
-- 

CREATE TABLE `ref_location_spellings` (
  `location_id` int(11) NOT NULL,
  `alt_spelling` varchar(255) collate latin1_general_ci NOT NULL,
  UNIQUE KEY `location_id` (`location_id`,`alt_spelling`),
  KEY `alt_spelling` (`alt_spelling`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- --------------------------------------------------------

-- 
-- Table structure for table `ref_relation`
-- 

CREATE TABLE `ref_relation` (
  `trace` varchar(20) collate latin1_general_ci NOT NULL,
  `distance` bigint(20) NOT NULL default '99999999999',
  `description` varchar(100) collate latin1_general_ci NOT NULL,
  `reverse` varchar(100) collate latin1_general_ci NOT NULL,
  `permission` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`trace`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `ref_relation`
-- 

INSERT INTO `ref_relation` (`trace`, `distance`, `description`, `reverse`, `permission`) VALUES 
('C', 2, 'child', 'parent', 4),
('CC', 11, 'grandchild', 'grandparent', 2),
('CCC', 29, 'great-grandchild', 'great-grandparent', 1),
('CCS', 28, 'grandchild''s spouse', 'spouse''s grandparent', 1),
('CS', 8, 'son/daughter-in-law', 'father/mother-in-law', 3),
('CSC', 27, 'son/daughter-in-law''s child', 'step-parent''s parent', 1),
('CSP', 26, 'son/daughter-in-law''s parent', 'son/daughter-in-law''s parent', 1),
('CSPC', 47, 'son/daughter-in-law''s sibling', 'brother/sister-in-law''s parent', 1),
('P', 3, 'parent', 'child', 3),
('PC', 5, 'sibling', 'sibling', 3),
('PCC', 15, 'niece/nephew', 'aunt/uncle', 2),
('PCCC', 39, 'niece/nephew''s child', 'great-uncle', 1),
('PCCS', 38, 'niece/nephew''s spouse', 'spouse''s grandparent''s child', 1),
('PCS', 14, 'brother/sister-in-law', 'brother/sister-in-law', 2),
('PCSC', 37, 'brother/sister-in-law''s child', 'step-parent''s brother/sister', 1),
('PCSP', 36, 'brother/sister-in-law''s parent', 'son/daughter-in-law''s sibling', 1),
('PP', 9, 'grandparent', 'grandchild', 2),
('PPC', 12, 'aunt/uncle', 'niece/nephew', 2),
('PPCC', 17, 'cousin', 'cousin', 2),
('PPCCC', 54, 'cousin''s child', 'great-uncle''s child', 1),
('PPCCS', 53, 'cousin''s spouse', 'spouse''s cousin', 1),
('PPCS', 16, 'aunt/uncle''s spouse', 'brother/sister-in-law''s child', 2),
('PPP', 18, 'great-grandparent', 'great-grandchild', 1),
('PPPC', 32, 'great-aunt/uncle', 'niece/nephew''s child', 1),
('PPPCC', 52, 'parent''s cousin', 'cousin''s child', 1),
('PPPCCC', 62, '2nd cousin', '2nd cousin', 1),
('PPPCCS', 61, 'parent''s cousin''s spouse', 'spouse''s cousin''s child', 1),
('PPPP', 30, '2nd great-grandparent', '2nd great-grandchild', 1),
('PPPPC', 50, 'great-grandparent''s sibling', 'niece/nephew''s grandchild', 1),
('PPPPP', 48, '3rd great-grandparent', '3rd great-grandchild', 1),
('PPPPPP', 60, '4th great-grandparent', '4th great-grandchild', 1),
('PPPPPPP', 67, '5th great-grandparent', '5th great-grandchild', 1),
('PPPPPPPP', 69, '6th great-grandparent', '6th great-grandchild', 1),
('PPPPPPPPP', 71, '7th great-grandparent', '7th great-grandchild', 1),
('PPPPPPPPPP', 73, '8th great-grandparent', '8th great-grandchild', 1),
('PPPPPPPPPPP', 75, '9th great-grandparent', '9th great-grandchild', 1),
('PPPPPPPPPPPP', 77, '10th great-grandparent', '10th great-grandchild', 1),
('PPPPPPPPPPPPP', 79, '11th great-grandparent', '11th great-grandchild', 1),
('PPPPS', 49, '2nd great-grandparent''s spouse', 'spouse''s 2nd great-grandchild', 1),
('PPPS', 31, 'great-grandparent''s spouse', 'step-great-grandchild', 1),
('PPPSC', 51, 'great-grandparent''s step child', 'step-parent''s great-grandchild', 1),
('PPS', 19, 'grandparent''s spouse', 'step child''s child', 1),
('PPSC', 33, 'grandparent''s step child', 'step sibling''s child', 1),
('PS', 4, 'step parent', 'step child', 3),
('PSC', 13, 'step sibling', 'step sibling', 2),
('PSCC', 35, 'step sibling''s child', 'grandparent''s step child', 1),
('PSCS', 34, 'step sibling''s spouse', 'father/mother-in-law''s step child', 1),
('PSP', 20, 'step-parent''s parent', 'son/daughter-in-law''s child', 1),
('S', 1, 'spouse', 'spouse', 4),
('SC', 7, 'step child', 'step parent', 3),
('SCC', 25, 'step child''s child', 'grandparent''s spouse', 1),
('SCS', 24, 'step child''s spouse', 'father/mother-in-law''s spouse', 1),
('SP', 6, 'father/mother-in-law', 'son/daughter-in-law', 3),
('SPC', 23, 'brother/sister-in-law', 'brother/sister-in-law', 1),
('SPCC', 46, 'spouse''s niece/nephew', 'aunt/uncle''s spouse', 1),
('SPCCS', 57, 'spouse''s niece/nephew''s spouse', 'spouse''s aunt/uncle''s spouse', 1),
('SPCS', 45, 'brother/sister-in-law''s spouse', 'brother/sister-in-law''s spouse', 1),
('SPP', 21, 'spouse''s grandparent', 'grandchild''s spouse', 1),
('SPPC', 42, 'spouse''s aunt/uncle', 'niece/nephew''s spouse', 1),
('SPPCC', 59, 'spouse''s cousin', 'cousin''s spouse', 1),
('SPPCCC', 66, 'spouse''s cousin''s child', 'parent''s cousin''s spouse', 1),
('SPPCCS', 65, 'spouse''s cousin''s spouse', 'spouse''s cousin''s spouse', 1),
('SPPCS', 58, 'spouse''s aunt/uncle''s spouse', 'spouse''s niece/nephew''s spouse', 1),
('SPPP', 40, 'spouse''s great-grandparent', 'great-grandchild''s spouse', 1),
('SPPPC', 56, 'spouse''s grandparent''s sibling', 'sibling''s grandchild''s spouse', 1),
('SPPPCC', 64, 'spouse''s parent''s cousin', 'cousin''s son/daughter-in-law', 1),
('SPPPP', 55, 'spouse''s 2nd great-grandparent', '2nd great-grandchild''s spouse', 1),
('SPPPPP', 63, 'spouse''s 3rd great-grandparent', '3rd great-grandchild''s spouse', 1),
('SPPPPPP', 68, 'spouse''s 4th great-grandparent', '4th great-grandchild''s spouse', 1),
('SPPPPPPP', 70, 'spouse''s 5th great-grandparent', '5th great-grandchild''s spouse', 1),
('SPPPPPPPP', 72, 'spouse''s 6th great-grandparent', '6th great-grandchild''s spouse', 1),
('SPPPPPPPPP', 74, 'spouse''s 7th great-grandparent', '7th great-grandchild''s spouse', 1),
('SPPPPPPPPPP', 76, 'spouse''s 8th great-grandparent', '8th great-grandchild''s spouse', 1),
('SPPPPPPPPPPP', 78, 'spouse''s 9th great-grandparent', '9th great-grandchild''s spouse', 1),
('SPPPPPPPPPPPP', 80, 'spouse''s 10th great-grandparent', '10th great-grandchild''s spouse', 1),
('SPPS', 41, 'spouse''s grandparent''s spouse', 'spouse''s grandchild''s spouse', 1),
('SPS', 22, 'father/mother-in-law''s spouse', 'step child''s spouse', 1),
('SPSC', 44, 'father/mother-in-law''s step child', 'step sibling''s spouse', 1),
('SPSP', 43, 'parent-in-law''s parent-in-law', 'child-in-law''s child-in-law', 1),
('SS', 10, 'spouse''s spouse', 'spouse''s spouse', 2),
('X', 0, 'self', 'self', 4);

-- --------------------------------------------------------

-- 
-- Table structure for table `ref_temple`
-- 

CREATE TABLE `ref_temple` (
  `temple_code` varchar(10) NOT NULL,
  `temple_name` varchar(50) NOT NULL,
  `short_code` char(2) default NULL,
  PRIMARY KEY  (`temple_code`),
  UNIQUE KEY `temple_name` (`temple_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of LDS temples';

-- 
-- Dumping data for table `ref_temple`
-- 

INSERT INTO `ref_temple` (`temple_code`, `temple_name`, `short_code`) VALUES 
('ALBER', 'ALBERTA, CANADA', 'AL'),
('APIA', 'APIA SAMOA', 'AP'),
('ARIZO', 'ARIZONA', 'AZ'),
('ATLAN', 'ATLANTA, GA', 'AT'),
('BAIRE', 'BUENOS AIRES', 'BA'),
('BOGOT', 'BOGOTA COL.', 'BG'),
('BOISE', 'BOISE ID', 'BO'),
('BOUNT', 'BOUNTIFUL UT', ''),
('CHICA', 'CHICAGO IL', 'CH'),
('COCHA', 'COCHABAMBA, BOLIVA', ''),
('DALLA', 'DALLAS, TX', 'DA'),
('DENVE', 'DENVER, CO', 'DV'),
('EHOUS', 'ENDOWMENT HOUSE', 'EH'),
('FRANK', 'FRANKFURT', 'FR'),
('FREIB', 'FREIBERG', 'FD'),
('GUATE', 'GUATAMALA', 'GA'),
('GUAYA', 'GUAYAQUIL, ECUADOR', 'GY'),
('HARTF', 'HARTFORD, CONN', ''),
('HAWAI', 'HAWAII', 'HA'),
('HKONG', 'HONG KONG', ''),
('IFALL', 'IDAHO FALLS, ID', 'IF'),
('JOHAN', 'JOHANNESBURG, S.A.', 'JO'),
('JRIVE', 'JORDAN RIVER, UT', 'JR'),
('LANGE', 'LOS ANGELES, CA', 'LA'),
('LIMA', 'LIMA, PERU', 'LI'),
('LOGAN', 'LOGAN, UT', 'LG'),
('LONDO', 'LONDON', 'LD'),
('LVEGA', 'LAS VEGAS, NV', 'LV'),
('MADRI', 'MADRID, SPAIN', ''),
('MANIL', 'MANILA, PHILIPPINES', 'MA'),
('MANTI', 'MANTI, UT', 'MT'),
('MEXIC', 'MEXICO CITY', 'MX'),
('MTIMP', 'MT. TIMPANOGAS, UT', ''),
('NASHV', 'NASHVILLE, TENN', ''),
('NAUVO', 'NAUVOO, IL', ''),
('NUKUA', 'NUKU''ALOFA, TONGA', 'TG'),
('NZEAL', 'NEW ZEALAND', 'NZ'),
('OAKLA', 'OAKLAND, CA', 'OK'),
('OGDEN', 'OGDEN, UT', 'OG'),
('ORLAN', 'ORLANDO, FL', ''),
('PAPEE', 'PAPEETE, TAHITI', 'TA'),
('POFFI', 'PRESIDENT''S OFFICE', ''),
('PORTL', 'PORTLAND, OR', 'PT'),
('PREST', 'PRESTON, ENGLAND', ''),
('PROVO', 'PROVO, UT', 'PV'),
('RECIF', 'RECIFE, BRAZIL', ''),
('SANTI', 'SANTIAGO, CHILE', 'SN'),
('SDIEG', 'SAN DIEGO, CA', 'SA'),
('SDOMI', 'SANTO DOMINGO, D.R.', ''),
('SEATT', 'SEATTLE, WA', 'SE'),
('SEOUL', 'SEOUL, KOREA', 'SO'),
('SGEOR', 'ST. GEORGE, UT', 'SG'),
('SLAKE', 'SALT LAKE, UT', 'SL'),
('SLOUI', 'ST. LOUIS, MISSOURI', ''),
('SPAUL', 'SAO PAULO, BRAZ', 'SP'),
('STOCK', 'STOCKHOLM, SWDN', 'ST'),
('SWISS', 'SWISS', 'SW'),
('SYDNE', 'SYDNEY, AUST', 'SD'),
('TAIPE', 'TAIPEI, TAIWAN', 'TP'),
('TOKYO', 'TOKYO, JAPAN', 'TK'),
('TORON', 'TORONTO, CANADA', 'TR'),
('VERNA', 'VERNAL, UT', ''),
('WASHI', 'WASHINGTON, DC', 'WA');

