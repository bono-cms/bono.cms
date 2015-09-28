
DROP TABLE IF EXISTS `bono_module_photoalbum_albums`;
CREATE TABLE `bono_module_photoalbum_albums` (

	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL COMMENT 'Language identificator',
	`parent_id` INT NOT NULL COMMENT 'Parent album id in current table',
	`web_page_id` INT NOT NULL COMMENT 'Album web page id',
	`title` varchar(255) NOT NULL,
	`name`  varchar(255) NOT NULL,
	`description` TEXT NOT NULL COMMENT 'Album description that comes from WYSIWYG',
	`order` INT NOT NULL COMMENT 'Sort order',
	`keywords` TEXT NOT NULL COMMENT 'Keywords for SEO',
	`seo` varchar(1) NOT NULL,
	`meta_description` TEXT NOT NULL COMMENT 'Meta description for SEO',
	`cover` varchar(255) NOT NULL

) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_photoalbum_photos`;
CREATE TABLE `bono_module_photoalbum_photos` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`album_id` INT NOT NULL,
	`name` varchar(254) NOT NULL,
	`photo` varchar(254) NOT NULL,
	`order` INT NOT NULL COMMENT 'Sort order',
	`description` TEXT NOT NULL,
	`published` varchar(1) NOT NULL,
	`date` INT NOT NULL COMMENT 'Timestamp of uploading'
	
) DEFAULT CHARSET = UTF8;
