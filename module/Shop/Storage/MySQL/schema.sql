

DROP TABLE IF EXISTS `bono_module_shop_orders_info`;
CREATE TABLE `bono_module_shop_orders_info` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique order id',
	`timestamp` INT NOT NULL,
	`name` varchar(255) NOT NULL COMMENT 'Name of customer',
	`phone` varchar(254) NOT NULL COMMENT 'Phone of customer',
	`address` TEXT NOT NULL COMMENT 'Destination address',
	`comment` TEXT NOT NULL COMMENT 'Customer comment',
	`delivery` TEXT NOT NULL COMMENT 'Delivery type',
	`qty` int NOT NULL COMMENT 'Ammount of products',
	`total_price` FLOAT COMMENT 'Total price',
	`approved` varchar(1) NOT NULL COMMENT 'Whether this order is approved'
	
) DEFAULT CHARSET = UTF8;



DROP TABLE IF EXISTS `bono_module_shop_orders_products`;
CREATE TABLE `bono_module_shop_orders_products` (
	
	`order_id` INT NOT NULL,
	`product_id` INT NOT NULL COMMENT 'Product id',
	`name` varchar(255) NOT NULL COMMENT 'Product name',
	`price` float NOT NULL COMMENT 'Product price',
	`sub_total_price` float NOT NULL COMMENT 'Sub-total price',
	`qty` INT NOT NULL COMMENT 'Amount of ordered products'
	
) DEFAULT CHARSET = UTF8;




DROP TABLE IF EXISTS `bono_module_shop_categories`;
CREATE TABLE `bono_module_shop_categories` (

	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`parent_id` INT NOT NULL COMMENT 'Parent category id this category id refers to',
	`web_page_id` INT NOT NULL COMMENT 'Sluggable web page id this category refers to',
	`title` varchar(254) NOT NULL COMMENT 'Title of the page',
	`description` TEXT NOT NULL COMMENT 'Full description of this category',
	`order` INT NOT NULL COMMENT 'Sort order for this category',
	`seo` varchar(1) NOT NULL COMMENT 'Whether SEO enabled or not',
	`keywords` TEXT NOT NULL COMMENT 'Keywords for search engines',
	`meta_description` TEXT COMMENT 'Meta description for search engines',
	`cover` varchar(254) NOT NULL COMMENT 'Cover image base name'
	
) DEFAULT CHARSET = UTF8;


DROP TABLE IF EXISTS `bono_module_shop_product_images`;
CREATE TABLE `bono_module_shop_product_images` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Image id',
	`product_id` INT NOT NULL,
	`image` varchar(254) NOT NULL COMMENT 'Image base name on file-system',
	`order` INT NOT NULL COMMENT 'Sort order',
	`published` varchar(1) NOT NULL COMMENT 'Whether this image is visible'
	
) DEFAULT CHARSET = UTF8;




DROP TABLE IF EXISTS `bono_module_shop_products`;
CREATE TABLE `bono_module_shop_products` (
	
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`lang_id` INT NOT NULL,
	`category_id` INT NOT NULL COMMENT 'Category id this product belongs to',
	`web_page_id` INT NOT NULL COMMENT 'Web page id this product refers to',
	`title` varchar(255) NOT NULL COMMENT 'Tile of this product',
	`regular_price` FLOAT NOT NULL COMMENT 'Regular price of this product',
	`stoke_price` FLOAT NOT NULL COMMENT 'Whether this product is considered as a special offer',
	`special_offer` varchar(1) NOT NULL COMMENT 'Whether this product is considered as a special offer',
	`description` TEXT NOT NULL COMMENT 'Full description` of this product',
	`published` varchar(1) NOT NULL COMMENT 'Whether this product should be visible on site',
	`order` INT NOT NULL COMMENT 'Sort order of this product',
	`seo` varchar(1) NOT NULL COMMENT 'Whether SEO tool is enabled or not',
	`keywords` TEXT NOT NULL COMMENT 'Keywords for search engines',
	`meta_description` TEXT NOT NULL COMMENT 'Meta-description for search engines',
	`cover` varchar(254) NOT NULL COMMENT 'Basename of image file',
	`timestamp` INT(10) NOT NULL COMMENT 'Timestamp of added data'
	
) DEFAULT CHARSET = UTF8;

