
DROP TABLE IF EXISTS `bono_module_slider_category`; 
CREATE TABLE `bono_module_slider_category` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_Id` INT NOT NULL,
	`name` varchar(255) NOT NULL,
	`class` varchar(255) NOT NULL COMMENT 'Class to simplify rendering',
	`width` FLOAT NOT NULL,
	`height` FLOAT NOT NULL
) DEFAULT CHARSET = UTF8;


DROP TABLE IF EXISTS `bono_module_slider_images`;
CREATE TABLE `bono_module_slider_images` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`category_id` INT NOT NULL,
	`name` varchar(254) NOT NULL,
	`description` TEXT NOT NULL,
	`order` INT NOT NULL,
	`published` varchar(1) NOT NULL,
	`link` varchar(255) NOT NULL,
	`image` varchar(254) NOT NULL
	
) DEFAULT CHARSET = UTF8;
