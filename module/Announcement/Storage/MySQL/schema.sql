
DROP TABLE IF EXISTS `bono_module_announcement_announces`;
CREATE TABLE `bono_module_announcement_announces` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`web_page_id` INT NOT NULL,
	`category_id` INT NOT NULL,
	`title` varchar(250) NOT NULL,
	`name` varchar(250) NOT NULL,
	`intro` TEXT NOT NULL,
	`full` TEXT NOT NULL,
	`order` INT NOT NULL,
	`published` varchar(1) NOT NULL,
	`seo` varchar(1) NOT NULL,
	`keywords` TEXT NOT NULL,
	`meta_description` TEXT NOT NULL
	
) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_announcement_categories`;
CREATE TABLE `bono_module_announcement_categories` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`name` varchar(254) NOT NULL,
	`class` varchar(255) NOT NULL COMMENT 'Class to simplify rendering'
	
) DEFAULT CHARSET = UTF8;
