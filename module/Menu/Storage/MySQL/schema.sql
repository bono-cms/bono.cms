
DROP TABLE IF EXISTS `bono_module_menu_categories`;
CREATE TABLE `bono_module_menu_categories` (

	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL COMMENT 'Language identification',
	`name` varchar(254) NOT NULL,
	`max_depth` INT NOT NULL,
	`class` varchar(254) NOT NULL

) DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `bono_module_menu_items`;
CREATE TABLE `bono_module_menu_items` (

	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary item id',
	`lang_id` INT NOT NULL COMMENT 'Language id attached to the item',
	`parent_id` INT NOT NULL COMMENT 'Item single parent id',
	`category_id` INT NOT NULL COMMENT 'Category id which current item belongs to',
	`web_page_id` varchar(11) COMMENT 'Web page identification this item is attached to',
	`range`	INT NULL COMMENT 'Sort range',
	`name` varchar(254) NOT NULL COMMENT 'Item name',
	`link` varchar(254) NOT NULL COMMENT 'Optional link in case not web_page_id attached',
	`has_link` varchar(1) NOT NULL COMMENT 'Indicates whether item should point to innter web page or to external resource',
	`hint` TEXT NOT NULL COMMENT 'A hint might be useful for presentation',
	`published` varchar(1) NOT NULL COMMENT 'Whether this item is visible or not',
	`open_in_new_window` varchar(1) NOT NULL COMMENT 'Whether an item should be opened in new window or not'

) DEFAULT CHARSET=UTF8;
