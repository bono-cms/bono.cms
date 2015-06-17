
DROP TABLE IF EXISTS `bono_module_advice`;
CREATE TABLE `bono_module_advice` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`title` varchar(254) NOT NULL,
	`content` TEXT NOT NULL,
	`published` varchar(1) NOT NULL
		
) DEFAULT CHARSET = UTF8;
