Announcement module
===================

Announcement module can be used to manage marketing features and special offers on your site. Announces usually have an introduction and a full description. Once you install the module, you can render announce stuff in templates, using a pre-defined service, which is called `$announcement`.

The service has only one method:

# getAllByClass($class)

It returns an array of announce entities by associated category class.

# Entities
Each announce entity has the following methods

# getTitle()

Returns announcement's title

# getName()

Returns announce's name

# getIntro() 

 Returns introduction text
 
# getFull()

Returns full description text

# getKeywords()

Returns a list of keywords for SEO

# getMetaDescription()

Returns meta description for SEO

# getUrl()

Returns URL to announce's page

# getPermanentUrl()

Returns permanent URL, that is independent of announce's slug.

# Example

This is how you're going to use it in templates mostly.

    foreach ($announcement->getAllByCLass('foo') as $announce) {
      <h2><?php echo $announce->getName(); ?></h2>
      <article><?php echo $announce->getIntro(); ?></article>
      <a href="<?php echo $announce->getUrl(); ?>">Learn more</a>
    }
    