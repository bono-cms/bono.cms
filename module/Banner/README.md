Banner module
=============

If you have flash banners on your site (typically with *.swf extension), then this module might be useful for you. The module has only one service, which is `$banner` with the following methods:

# getRandom()

Returns random banner's entity object.

# getById($id)

Returns banner's entity object by its associated id.

# Entity objects

An entity object has the following methods:

# getId()

Returns a unique ID of a banner.

# getName()

Returns the name of a banner

# getLink()

Returns the URL link of a banner if specified.

# getUrlPath()

Returns the  full URL this banner is located at.


# Example

That's how you're going to use it in templates (assuming there's a banner with id = 1):

```

<div>
 <embed src="<?php echo $banner->getById('1')->getUrlPath(); ?>" />
</div>

```
