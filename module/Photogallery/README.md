Photogallery module
===================

This module allows you to manage photo galleries on your site. Photo albums can be nested. To start using it, you need to create at least one album and add it to some category menu. Then you can start adding photos into that album. In album's template you can simply iterate over array of photo entities. Here are the following methods, that you're going to use:

# getName()

Returns photo name

# getDescription()

Returns photo description

# getImageBag()->getUrl($dimension)

Returns URL path to a photo with provided dimension

# Nested albums
To determine if an album has child albums, you can simply check if variable `$albums` exists. It it does, then its an array of child album entities, otherwise and album doesn't have children. For checking you can use `isset()` function, like this:
 

    <div>
     <?php if (isset($albums)): ?>
       // There are child albums, do iterate over this array somewhere
     <?php endif; ?>
    </div>


# Example: Rendering album photos

Note this example assumes, that you have set up 250x250 dimensions in configuration.

    <?php if (!empty($photos)): ?>
    <ul>
    
    <?php foreach ($photos as $photo): ?>
      <li><img src="<?php echo $photo->getImageBag()->getUrl('250x250'); ?>" alt="<?php echo $photo->getName(); ?>" /></li>
    <?php endforeach; ?>
    
    </ul>
    
    <?php endif; ?>

# Site service

There's also a pre-defined `$photogallery` service you can use in case you want to render members on another page. For instance, that can be a landing page where you want to render all photos from some album. The `$photogallery` service has only one method for this:

## getAll($id)

Returns an array of photo entities by given album id.

As you might already guess, the usage is the same. Just like as rendering `$photos` array in previous example, but instead, you'd substitute it with `$photogallery->getAll('..some id..')`.