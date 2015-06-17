
DROP TABLE IF EXISTS `bono_module_blog_categories`;
CREATE TABLE `bono_module_blog_categories` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL COMMENT 'Language id this category belongs to',
	`web_page_id` INT NOT NULL COMMENT 'Web page id this category belongs to',
	`title` varchar(255) NOT NULL COMMENT 'The title of the category',
	`description` TEXT NOT NULL COMMENT 'Description of the category',
	`seo` varchar(1) NOT NULL COMMENT 'Whether SEO enabled or not',
	`order` INT NOT NULL COMMENT 'Sort order',
	`keywords` TEXT NOT NULL COMMENT 'Meta keywords for SEO',
	`meta_description` TEXT NOT NULL COMMENT 'Meta description for SEO'
	
) DEFAULT CHARSET = UTF8;


DROP TABLE IF EXISTS `bono_module_blog_posts`;
CREATE TABLE `bono_module_blog_posts` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`web_page_id` INT NOT NULL,
	`category_id` INT NOT NULL,
	`title` varchar(254) NOT NULL,
	`introduction` TEXT NOT NULL,
	`full` TEXT NOT NULL,
	`timestamp` INT(10) NOT NULL,
	`published` varchar(1) NOT NULL,
	`comments` varchar(1) NOT NULL,
	`seo` varchar(1) NOT NULL,
	`keywords` TEXT NOT NULL,
	`meta_description` TEXT NOT NULL
	
) DEFAULT CHARSET = UTF8;
