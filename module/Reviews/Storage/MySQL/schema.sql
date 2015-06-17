

DROP TABLE IF EXISTS `bono_module_reviews`;

CREATE TABLE `bono_module_reviews` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`langId` INT NOT NULL,
	`timestamp` INT NOT NULL,
	`ip` varchar(254) NOT NULL,
	`published` varchar(1) NOT NULL,
	`name` varchar(254) NOT NULL,
	`email` varchar(254) NOT NULL,
	`content` TEXT
	
) DEFAULT CHARSET = UTF8;

