Pages module
============

Pages module is a core module of your web site. It lets you to attach pages with different controllers and layouts. Basically you would need the following methods when working with pages inside templates:

# getTitle()

Returns page title

# getContent()

Returns page's content

# getDefault()

Determines whether a page is default one (i.e home page)

# getUrl()

Returns the URL

# Theme templates

There are 3 templates you need to create in your theme folder, where you can use aforementioned methods.

# pages-page.phtml

Template for all static pages

# pages-home.phtml

Template for home page

# pages-404.phtml

Template for 404 page