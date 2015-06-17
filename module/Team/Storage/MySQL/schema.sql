

DROP TABLE IF EXISTS `bono_module_team`;
CREATE TABLE `bono_module_team` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`name` varchar(255) NOT NULL COMMENT "Member's name",
	`description` TEXT NOT NULL COMMENT "Member description or biography",
	`photo` varchar(255) NOT NULL,
	`published` varchar(1) NOT NULL,
	`order` INT NOT NULL COMMENT "Sort order"
	
) DEFAULT CHARSET = UTF8;
