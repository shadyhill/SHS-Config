# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.14)
# Database: atx_xmaslist
# Generation Time: 2014-10-17 04:14:50 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table config_admin_logins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_admin_logins`;

CREATE TABLE `config_admin_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `manager_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pass` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `permission` int(2) DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table config_form_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_form_fields`;

CREATE TABLE `config_form_fields` (
  `form_id` int(11) DEFAULT NULL,
  `f_order` int(3) DEFAULT NULL,
  `tab_order` int(3) DEFAULT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `name_id` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `placeholder` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `dd_displays` text CHARACTER SET utf8 COMMENT 'select menu displays',
  `dd_values` text CHARACTER SET utf8 COMMENT 'select menu values',
  `hint_txt` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `has_error` int(2) DEFAULT NULL,
  `error_txt` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `width` int(4) DEFAULT NULL,
  `class_override` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `style_override` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `stamp` date DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `config_form_fields` WRITE;
/*!40000 ALTER TABLE `config_form_fields` DISABLE KEYS */;

INSERT INTO `config_form_fields` (`form_id`, `f_order`, `tab_order`, `type`, `label`, `name_id`, `placeholder`, `required`, `dd_displays`, `dd_values`, `hint_txt`, `has_error`, `error_txt`, `width`, `class_override`, `style_override`, `stamp`, `updated`)
VALUES
	(1,1,NULL,'text','USERNAME','f_user','Manager Login',0,NULL,NULL,NULL,0,NULL,300,'',NULL,'2012-03-27','2012-03-27 20:46:56'),
	(1,2,NULL,'password','PASSWORD','f_pass','Password',0,NULL,NULL,NULL,0,NULL,300,NULL,NULL,'2012-03-27','2012-03-27 20:46:58'),
	(2,2,NULL,'text','Page URL','page_url','url/with/trailing/slash/',0,NULL,NULL,NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-03 23:13:42'),
	(2,1,NULL,'text','Page Name','page_name','Name of Page',0,NULL,NULL,NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-03 23:13:41'),
	(2,8,NULL,'checkbox','','is_index',NULL,0,'Is Index','',NULL,NULL,NULL,300,NULL,NULL,NULL,'2013-07-03 23:06:57'),
	(2,9,NULL,'checkbox','','override_path',NULL,0,'Override Path?','',NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-03 23:13:40'),
	(2,3,NULL,'text','Include File','include_file','some/path/file.php',0,NULL,NULL,NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-04 08:42:23'),
	(2,5,NULL,'text','Page Title (Meta)','meta_title','Page Title',0,NULL,NULL,NULL,NULL,NULL,300,NULL,NULL,NULL,'2013-07-03 23:06:51'),
	(2,6,NULL,'textarea','Meta Description','meta_description','Meta description text for search engines.',0,NULL,NULL,NULL,NULL,NULL,300,'span5',NULL,NULL,'2013-07-04 08:46:19'),
	(2,7,NULL,'text','Meta Keywords','meta_keywords',NULL,0,NULL,NULL,NULL,NULL,NULL,300,NULL,NULL,NULL,'2013-07-03 23:06:54'),
	(3,1,NULL,'text','Page Name','page_name','Name of Page',0,NULL,NULL,NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-03 23:13:41'),
	(3,2,NULL,'text','Page URL','page_url','url/with/trailing/slash/',0,NULL,NULL,NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-03 23:13:42'),
	(3,3,NULL,'text','Include File','include_file','some/path/file.php',0,NULL,NULL,NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-04 08:42:23'),
	(3,5,NULL,'text','Page Title (Meta)','meta_title','Page Title',0,NULL,NULL,NULL,NULL,NULL,300,NULL,NULL,NULL,'2013-07-03 23:06:51'),
	(3,6,NULL,'textarea','Meta Description','meta_description','Meta description text for search engines.',0,NULL,NULL,NULL,NULL,NULL,300,'span5',NULL,NULL,'2013-07-04 08:46:19'),
	(3,7,NULL,'text','Meta Keywords','meta_keywords',NULL,0,NULL,NULL,NULL,NULL,NULL,300,NULL,NULL,NULL,'2013-07-03 23:06:54'),
	(3,8,NULL,'checkbox','','is_index',NULL,0,'Is Index','',NULL,NULL,NULL,300,NULL,NULL,NULL,'2013-07-03 23:06:57'),
	(3,9,NULL,'checkbox','','override_path',NULL,0,'Override Path?','',NULL,NULL,NULL,300,'span4',NULL,NULL,'2013-07-03 23:13:40'),
	(3,10,NULL,'hidden',NULL,'page_id',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-04 15:03:58'),
	(4,1,NULL,'text','*Form Name','form_name','Name the form',0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-06 15:12:41'),
	(4,2,NULL,'text','*AJAX URL','ajax_action_url','manager/AJAX/obj/fx/',0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-07 14:16:09'),
	(4,9,NULL,'selectmenu','Form Type','form_type',NULL,0,'AJAX|DEFAULT (POST)','AJAX|DEFAULT (POST)',NULL,NULL,NULL,NULL,'span2',NULL,NULL,'2013-07-06 15:12:13'),
	(4,8,NULL,'text','Action','action',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-06 15:12:11'),
	(4,5,NULL,'text','On Submit Function','onsubmit','submitAJAX',0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-06 15:13:15'),
	(4,10,NULL,'selectmenu','Method','method',NULL,0,'post|get','post|get',NULL,NULL,NULL,NULL,'span2',NULL,NULL,'2013-07-06 15:12:15'),
	(4,4,NULL,'text','Button Text','button_txt','Submit',0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-06 15:12:03'),
	(4,11,NULL,'selectmenu','Encoding','encoding',NULL,0,'None|Multipart','|multipart/form-data',NULL,NULL,NULL,NULL,'span2',NULL,NULL,'2013-07-06 15:12:17'),
	(4,6,NULL,'text','Preprocess Function','pre_process',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-06 15:12:06'),
	(4,7,NULL,'text','Postprocess Function','post_process',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-06 15:12:09'),
	(4,3,NULL,'text','*Class','form_class','form-horizontal',0,NULL,NULL,NULL,NULL,NULL,NULL,'span4',NULL,NULL,'2013-07-06 15:14:12'),
	(5,1,NULL,'selectmenu','Type','type',NULL,0,'Text Box|Password|Textarea|Select Menu|Check Box|DB Select Menu','text|password|textarea|selectmenu|checkbox|dbselectmenu',NULL,NULL,NULL,NULL,'',NULL,NULL,'2013-07-08 23:54:57'),
	(5,2,NULL,'text','Label','label',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:52:41'),
	(5,3,NULL,'text','Name/ID','name_id',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:52:55'),
	(5,4,NULL,'text','Placeholder','placeholder',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:53:07'),
	(5,5,NULL,'text','Class Override','class_override','span4',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:53:27'),
	(5,6,NULL,'text','Style Override','style_override',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:53:46'),
	(5,7,NULL,'textarea','Displays','dd_displays',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:54:16'),
	(5,8,NULL,'textarea','Values','dd_values',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:54:34'),
	(5,9,NULL,'hidden','','form_id',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-07-08 23:55:26');

/*!40000 ALTER TABLE `config_form_fields` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table config_forms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_forms`;

CREATE TABLE `config_forms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `ajax_url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `form_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `method` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `onsubmit` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `pre_process` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_process` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `form_class` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `button_txt` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `encoding` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `has_error_field` int(2) DEFAULT NULL,
  `stamp` date DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `config_forms` WRITE;
/*!40000 ALTER TABLE `config_forms` DISABLE KEYS */;

INSERT INTO `config_forms` (`id`, `form_name`, `ajax_url`, `form_type`, `action`, `method`, `onsubmit`, `pre_process`, `post_process`, `form_class`, `button_txt`, `encoding`, `has_error_field`, `stamp`, `updated`)
VALUES
	(1,'manager-login','manager/ajax/manager/login/','AJAX','#manager-login','post','validateMLogin',NULL,NULL,'managerForm','LOGIN',NULL,1,'2012-03-27','2013-10-07 21:07:01'),
	(2,'m-settings-page-create','manager/ajax/pages/create/','AJAX','#create-m-settings-page','post','submitAJAX',NULL,NULL,'form-horizontal','CREATE PAGE',NULL,1,NULL,'2013-10-07 21:06:58'),
	(3,'m-settings-page-edit','manager/ajax/pages/edit/','AJAX','#create-m-settings-edit','post','submitAJAX',NULL,NULL,'form-horizontal','EDIT PAGE',NULL,1,NULL,'2013-10-07 21:06:53'),
	(4,'m-new-form','manager/ajax/forms/create/','AJAX','#create-m-form','post','submitAJAX',NULL,NULL,'form-horizontal','CREATE FORM',NULL,1,NULL,'2013-10-07 21:06:50'),
	(5,'m-form-add-field','manager/ajax/forms/addField/','AJAX','#m-form-add-field','post','submitAJAX',NULL,NULL,'form-horizontal','Add Field','',NULL,NULL,'2013-10-07 21:06:47'),
	(10,'Contact Form','ajax/contact/create/','AJAX','#create-contact','post','submitAJAX',NULL,NULL,'form-horizontal','Send Message',NULL,NULL,NULL,'2013-10-07 21:06:43'),
	(11,'Account SignIn','ajax/account/authenticate/','AJAX','#account-signin','post','submitAJAX',NULL,NULL,'','Sign In &nbsp; <i class=\'ionicons ion-arrow-right-a\'></i>',NULL,1,NULL,'2014-04-12 14:12:01'),
	(12,'Forgot Password','ajax/account/forgot/','AJAX','#account-forgot','post','submitAJAX',NULL,NULL,NULL,'Remind Me',NULL,1,NULL,'2014-03-30 14:05:57'),
	(13,'Add Contact','ajax/contact/create/','AJAX','#contact-create','post','submitAJAX',NULL,NULL,NULL,'Add Contact',NULL,NULL,NULL,'2014-04-13 23:36:49'),
	(14,'New Project','ajax/project/create/','AJAX','#project-create','post','submitAJAX',NULL,NULL,NULL,'Create Project',NULL,1,NULL,'2014-05-04 08:51:43');

/*!40000 ALTER TABLE `config_forms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table config_manager_logins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_manager_logins`;

CREATE TABLE `config_manager_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `manager_id` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `user` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `pass` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `permission` int(2) DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table config_page_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_page_data`;

CREATE TABLE `config_page_data` (
  `router_id` int(11) DEFAULT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `view_class` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_fx` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `meta_keywords` varchar(255) CHARACTER SET utf8 DEFAULT '',
  KEY `router_id` (`router_id`),
  CONSTRAINT `router_id_fk` FOREIGN KEY (`router_id`) REFERENCES `config_url_router` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `config_page_data` WRITE;
/*!40000 ALTER TABLE `config_page_data` DISABLE KEYS */;

INSERT INTO `config_page_data` (`router_id`, `template`, `view_path`, `view_class`, `view_fx`, `meta_title`, `meta_description`, `meta_keywords`)
VALUES
	(1,'home/index.html','views/home','general','index','Shady Hill Studios','Shady Hill Studios Config Framework','SHS');

/*!40000 ALTER TABLE `config_page_data` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table config_page_scripts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_page_scripts`;

CREATE TABLE `config_page_scripts` (
  `router_id` int(11) DEFAULT NULL,
  `script` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `script_type` enum('css','js') COLLATE utf8_unicode_ci DEFAULT NULL,
  `load_order` int(3) DEFAULT NULL,
  KEY `router_id` (`router_id`),
  CONSTRAINT `router_id` FOREIGN KEY (`router_id`) REFERENCES `config_url_router` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `config_page_scripts` WRITE;
/*!40000 ALTER TABLE `config_page_scripts` DISABLE KEYS */;

INSERT INTO `config_page_scripts` (`router_id`, `script`, `script_type`, `load_order`)
VALUES
	(10,'underscore/underscore-min.js','js',10),
	(10,'backbone/backbone-min.js','js',20),
	(10,'handlebars/handlebars-v1.1.2.js','js',30),
	(10,'src/rfp.js','js',60),
	(10,'templates/compiled-templates.js','js',50);

/*!40000 ALTER TABLE `config_page_scripts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table config_url_router
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_url_router`;

CREATE TABLE `config_url_router` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `url_pattern` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `request` enum('render','process','ajax','email','log','api','content') CHARACTER SET utf8 DEFAULT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_path` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_class` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_method` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `access` enum('public','manager','admin','api') CHARACTER SET utf8 DEFAULT NULL,
  `secure` int(2) NOT NULL DEFAULT '0' COMMENT 'Is the page served on SSL?',
  `validate` int(2) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  `parse_order` int(4) NOT NULL DEFAULT '100',
  `created` date DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `config_url_router` WRITE;
/*!40000 ALTER TABLE `config_url_router` DISABLE KEYS */;

INSERT INTO `config_url_router` (`id`, `title`, `url_pattern`, `request`, `template`, `view_path`, `view_class`, `view_method`, `access`, `secure`, `validate`, `status`, `parse_order`, `created`, `stamp`)
VALUES
	(1,'Home Page','/','render','home/index.html','views/home','general','index','public',0,0,1,0,'2013-10-06','2013-10-06 09:21:48'),
	(3,'Default Processor','process/:model[w]/:method[w]','process',NULL,NULL,NULL,NULL,'public',0,0,1,999,'2013-10-06','2013-10-06 21:48:12'),
	(4,'Default AJAX','ajax/:model[w]/:method[w]','ajax',NULL,NULL,NULL,NULL,'public',0,0,1,999,'2013-10-06','2013-10-06 21:48:12'),
	(5,'Default Manager Processor','manager/process/:model[w]/:method[w]','process',NULL,NULL,NULL,NULL,'manager',1,0,1,999,'2013-10-06','2013-10-06 21:48:12'),
	(6,'Default Manager AJAX','manager/ajax/:model[w]/:method[w]','ajax',NULL,NULL,NULL,NULL,'manager',1,0,1,999,'2013-10-06','2013-10-06 21:48:12'),
	(8,'Default Mailer','email/:method[w]','email',NULL,NULL,NULL,NULL,'public',1,0,1,999,'2013-10-06','2013-10-07 00:23:23'),
	(9,'Default Logger','log/:method[w]','log',NULL,NULL,NULL,NULL,'public',1,0,1,999,'2013-10-06','2013-10-07 00:23:54'),
	(10,'Test','test/','render','home/test.html','views/home','general','test','public',0,0,1,100,NULL,'2014-03-16 13:55:39');

/*!40000 ALTER TABLE `config_url_router` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
