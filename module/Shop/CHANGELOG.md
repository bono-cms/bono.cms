CHANGELOG
=========

1.2
---

 * Set cookie lifetime for recent products to 631139040 seconds
 * Added support for starting prices in category. This functionality is implemented in `SiteService::getMinCategoryPriceCount`
 * Implemented support for "Mostly Viewed Products". Added `getProductsWithMaxViewCount()` to `SiteService` 
 * Added support for views count
 * Minor improvements in code base
 * Added support for permanent URLs. Now each product's entity has `getPermanentUrl()` method
 * Added `SiteService`, which is called `$shop`. Moved all related functionality there as well
 * Added filters to grid view
 * Removed a block of latest orders from grid view in administration panel
 * Added image zoom to product images (Thanks to light-box JS-plugin)


1.1
---

 * Added currency support
 * Added stoke price support
 * Improved cart's logic
 * Add "recently viewed products" functionality, which is optional


1.0
---

 * First public version