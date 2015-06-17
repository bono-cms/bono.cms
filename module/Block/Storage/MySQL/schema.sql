
DROP TABLE IF EXISTS `bono_module_block`;
CREATE TABLE `bono_module_block` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`name` varchar(255) NOT NULL,
	`content` TEXT NOT NULL,
	`class` varchar(255) NOT NULL
	
) DEFAULT CHARSET = UTF8;
