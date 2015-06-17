

DROP TABLE IF EXISTS `bono_module_pages`;
CREATE TABLE `bono_module_pages` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL COMMENT 'Language identificator',
	`web_page_id` INT NOT NULL COMMENT 'Web page identificator can be found in site module',
	`template` varchar(32) NOT NULL COMMENT 'Template override',
	`protected` varchar(1) NOT NULL COMMENT 'Whether this page is allowed to be removed in simple mode',
	`title` varchar(255) NOT NULL COMMENT 'Page title',
	`content` TEXT NOT NULL COMMENT 'Fits for description',
	`seo` varchar(1) NOT NULL COMMENT 'Whether it should be indexed in SEO',
	`keywords` TEXT NOT NULL,
	`meta_description` TEXT NOT NULL COMMENT 'Meta-description for search engines'
	
) DEFAULT CHARSET = UTF8;



DROP TABLE IF EXISTS `bono_module_pages_defaults`;
CREATE TABLE `bono_module_pages_defaults` (
	`id` INT NOT NULL PRIMARY KEY,
	`lang_id` INT NOT NULL
);
