
DROP TABLE IF EXISTS `bono_module_cms_webpages`;
CREATE TABLE `bono_module_cms_webpages` (
	
 `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Web page identificator',
 `lang_id` INT NOT NULL COMMENT 'Language identificator of this page',
 `target_id` INT NOT NULL COMMENT 'Id to be passed to a controller`',
 `slug` varchar(254) NOT NULL COMMENT 'URL path itself',
 `module` varchar(50) NOT NULL COMMENT '',
 `controller` varchar(254) NOT NULL COMMENT 'Framework-compliant controller',
 `lastmod` DATETIME NOT NULL COMMENT 'Last modification time'

) DEFAULT CHARSET=UTF8;

CREATE INDEX `webpage` ON `bono_module_cms_webpages`(slug) using HASH;


DROP TABLE IF EXISTS `bono_module_cms_notepad`;
CREATE TABLE `bono_module_cms_notepad` (

 `user_id` INT NOT NULL PRIMARY KEY COMMENT 'User id content belongs to',
 `content` TEXT NOT NULL COMMENT 'Notepad data itself'
 
) DEFAULT CHARSET = UTF8;



DROP TABLE IF EXISTS `bono_module_cms_notifications`;
CREATE TABLE `bono_module_cms_notifications` (

 `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 `timestamp` INT(10) NOT NULL COMMENT 'UNIX Timestamp of creation',
 `viewed` varchar(1) NOT NULL COMMENT 'Either 1 or 0. Incidates whether used read it or not yet',
 `message` TEXT COMMENT 'Notification message'
 
) DEFAULT CHARSET = UTF8;



DROP TABLE IF EXISTS `bono_module_cms_history`;
CREATE TABLE `bono_module_cms_history` (

 `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 `lang_id` INT NOT NULL,
 `user_id` INT NOT NULL,
 `timestamp` INT(10) NOT NULL,
 `module` varchar(255) NOT NULL,
 `comment` TEXT NOT NULL,
 `placeholder` varchar(250) NOT NULL
 
) DEFAULT CHARSET = UTF8;





DROP TABLE IF EXISTS `bono_module_cms_languages`;
CREATE TABLE `bono_module_cms_languages` (

 `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 `name` varchar(200),
 `code` varchar(30) NOT NULL,
 `flag` varchar(5) NOT NULL,
 `order` INT NOT NULL,
 `published` varchar(1) NOT NULL
 
) DEFAULT CHARSET = UTF8;




DROP TABLE IF EXISTS `bono_module_cms_users`;
CREATE TABLE `bono_module_cms_users` (

 `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 `login` varchar(254) NOT NULL,
 `password_hash` varchar(254) NOT NULL,
 `role` varchar(30) NOT NULL,
 `email` varchar(254) NOT NULL,
 `name` varchar(254) NOT NULL
 
) DEFAULT CHARSET = UTF8;

