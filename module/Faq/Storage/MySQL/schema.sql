
DROP TABLE IF EXISTS `bono_module_faq`;
CREATE TABLE `bono_module_faq` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL COMMENT 'Language identification',
	`question` TEXT	NOT NULL COMMENT 'Question text',
	`answer` TEXT NOT NULL COMMENT 'Answer text',
	`order` INT NOT NULL COMMENT 'Sort order',
	`published` varchar(1) NOT NULL COMMENT 'Whether is published or not'
	
) DEFAULT CHARSET = UTF8;
