Shop module
===========

This module allows you to manage e-commerce store on your site. It has the following features:

- Categories with unlimited depth support
- Products with several images support
- Support for stoke prices
- Sorting options and filters for attributes
- Basket (aka shopping cart) and ordering via sending emails
- Statistic about sold products
- History

To start using it, you would start using available services. So let's take a loot at available ones.

# Shop service

The service is represented by `$shop` variable. It has the following methods:

## getMinCategoryPriceCount($id)

Returns minimal product price (i.e starting price) inside that category.

## getProductsWithMaxViewCount($limit, $categoryId = null)

Returns an array of product entities that have maximum views. The`$limit` specifies amount of records to be returned. `$categoryId` allows to optionally filter by specific category id.

## getRecentProducts($id)

Returns an array of recently viewed products (by user). `$id`specifies a product id to be excluded from the collection if present. For example, when viewing some product's page, you don't want to appear current product which is being viewed in the collection, right? So supplying current product id as argument, will exclude it if present.

## getLatest()

Returns an array of entities of latest products that have been added by site administrator. An amount is defined in module configuration.


# Basket service

The service is represented by `$basket` variable. It has the following methods:

## getUrl()

Returns URL of basket's page. The id of the page must be supplied in module configuration to make it work.

## getTotalPrice()

Returns the total price of all products in the basket.

## getTotalQty()

Returns total amount of products in the basket.

## getCurrency()

Returns current currency. The currency must be defined in module's configuration.

# Templates

## Product template

The file must be called `shop-product.phtml` and must be located inside current theme directory. Inside this template, you'd have `$product` entity object with the following methods:

### getImageBag()->getUrl('...')

Returns full URL path to image. Depending on configured dimensions, you can get an image by that dimension.

### getCategoryName()

Returns a name of a category this product belong in.

### getTitle()

Returns the title of the product.

### getPrice()

Returns the price.

### getStokePrice()

Returns stoke price if defined.

### getSpecialOffer()

Returns either `TRUE` or `FALSE` indicating whether the product has been marked as "special offer".

### getDescription()

Returns full product description.


### getDate()

Returns a date when the product has been added by site administrator in `YEAR-MONTH-DAY` format.

### getUrl()

Returns product URL.

### getViewCount()

Returns amount of product views (by users)

## Category template

The file must be called `shop-category.phtml` and must be located inside current theme directory. There will be `$category` entity object with the following methods:


### getImageBag()->getUrl('...')

Returns a full URL path to cover image. Depending on configured dimensions, you can get an image by that dimension.

### getTitle()

Returns a title of the category

### getDescription()

Returns a description of the category.

### getUrl()

Returns the full URL to the current category.

## Basket template

The file must be called `shop-basket.phtml` and must be located inside current theme directory. Inside this template, you'd have `$products` array of product entity objects with the following methods:

### getId()

Returns unique product it.

### getTitle()

Returns product title.

### getImageBag()->getUrl('...')

Returns a full path to image URL.

### getQty()

Returns a quantity of the same product in the basket.

### getPrice()

Returns product price. It a product has a stoke price, then a stoke price is returned.

### getSubTotalPrice()

Returns sub-total price. Simply put, that's a result of multiplying quantity of copies with a price.


## Stoke page template

The file must be called `shop-stokes.phtml` and must be located inside current theme directory. This page has `$products` array of product entities. The methods are identical to the ones as in category page.


NOTE
====

If you're planning to use this module with several languages, please don't. 
While technically it would surely work, you might end up with products duplications.
Full internalization support is still under development and will be available soon (Where you can edit all translations for one product).
