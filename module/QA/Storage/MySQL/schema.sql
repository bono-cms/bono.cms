
DROP TABLE IF EXISTS `bono_module_qa`;
CREATE TABLE `bono_module_qa` (
	
	`id`				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`langId`			INT NOT NULL,
	`question`			TEXT NOT NULL,
	`answer`			TEXT NOT NULL,
	`questioner`		varchar(254) NOT NULL,
	`answerer`			varchar(254) NOT NULL,
	`published` 		varchar(1) NOT NULL,
	`timestampAsked`	INT(10) NOT NULL,
	`timestampAnswered` INT(10) NOT NULL
	
) DEFAULT CHARSET = UTF8;
