
DROP TABLE IF EXISTS `bono_module_banner`;

CREATE TABLE `bono_module_banner` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`name` varchar(254) NOT NULL,
	`link` varchar(254) NOT NULL,
	`image` varchar(254) NOT NULL
	
) DEFAULT CHARSET = UTF8;

