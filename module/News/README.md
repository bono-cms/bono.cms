News module
===========

This module allows you to manage news on your site. To start using it, you must create at least one category.

Each post entity has the following methods:


# getTitle()

Return post's title

# getIntro()

Returns post's introduction text

# getFull()

Returns post's full text

# getUrl()

Returns post's URL

# getImageBag()->getUrl($dimension)

Returns URL's path to the image

# getTimeBag()->getPostFormat()

Returns formatted date for the post's template

# getTimeBag()->getListFormat()

Returns formatted date for the category's template


# Example: Rendering posts in some category

    <?php if (!empty($posts)): ?>>
    <?php foreach ($posts as  $post): ?>
    
    <h2><?php echo $post->getTItle(); ?> (<?php echo $post->getTimeBag()->getListFormat(); ?>)</h2>
    <article><?php echo $post->getIntro(); ?></article>
    
    <a href="<?php echo $post->getUrl(); ?>">Read on</a>
    
    <?php endforeach; ?>
    <?php endif; ?>

Each category entity has the following methods:

# getTitle()

Returns category title

# getDescription()

Returns category's description

# getUrl()

Returns category's URL


