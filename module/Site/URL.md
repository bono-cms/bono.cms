URL Generation
=====
You can generate dynamic URLs to other pages using the built-in global method `$cms->createUrl($id, $module)`. This method takes a **target page ID** and a **module name** as parameters to create the appropriate URL.

## Why use `$cms->createUrl()`?

The key advantage of using this method is **flexibility**: if the target page’s URL ever changes, all links generated with `$cms->createUrl()` will automatically reflect the update — no manual changes needed.

## Example
Suppose you have a page with `ID = 1`, and you're linking to it through the `Pages` module:

    <a href="<?= $cms->createUrl(1, 'Pages'); ?>">View my page</a>

Each module that handles custom pages includes a dedicated section in its documentation explaining URL generation.