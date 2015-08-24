Advice module
=============

This module can help you to manage random advices (on each request a new one is shown) on your site. This can be useful to display any kind of stuff that needs to be random as well.

This module has one service `$advice` that has following methods:

# getRandom()

Returns random advice's entity

# getById()

Returns advice's entity by its associated id

Each entity has the following methods:

# getTitle()

Returns advice's title

# getContent()

Returns advice's content

# getId()

Returns unique id of an entity

# Usage in example
```
<?php 
  // Don't name it as $advice, because it's reserved name
  $adviceEntity = $advice->getRandom();
?>

<div>
  <h2><?php echo $adviceEntity->getTitle(); ?></h2>
  <article><?php echo $adviceEntity->getContent(); ?></article>
</div>
```
