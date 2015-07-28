
DROP TABLE IF EXISTS `bono_module_mailform`;
CREATE TABLE `bono_module_mailform` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`web_page_id` INT NOT NULL,
	`template` varchar(255) NOT NULL COMMENT 'Framework-compliant template view file',
	`message_view` varchar(255) NOT NULL COMMENT 'Framework-compliant message view',
	`title` varchar(255) NOT NULL,
	`description` TEXT NOT NULL,
	`seo` varchar(1) NOT NULL,
	`keywords` text NOT NULL,
	`meta_description` TEXT NOT NULL
	
) DEFAULT CHARSET = UTF8;