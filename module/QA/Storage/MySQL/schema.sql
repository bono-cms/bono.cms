
DROP TABLE IF EXISTS `bono_module_qa`;
CREATE TABLE `bono_module_qa` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`question` TEXT NOT NULL,
	`answer` TEXT NOT NULL,
	`questioner` varchar(254) NOT NULL,
	`answerer` varchar(254) NOT NULL,
	`published` varchar(1) NOT NULL,
	`timestamp_asked` INT(10) NOT NULL,
	`timestamp_answered` INT(10) NOT NULL
	
) DEFAULT CHARSET = UTF8;
