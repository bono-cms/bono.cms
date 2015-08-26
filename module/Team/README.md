Team module
===========
Sometimes you might want to introduce the members of your team to your visitors. This module helps you to manage members. To start using the module, you need to create new static page, which points to `Team:Team@indexAction` controller. Then you can start adding members of your team.

A template file in your theme folder should be called `team.phtml`. Then in the template, you'd have an array of member entities, which is called `$members`. Each entity has the following methods:

# getName()

Returns member's name

# getDescription()

Returns member's description

# getImageBag()->getUrl($dimension)

Returns image's URL by provided dimension. That dimension needs to be defined in configuration

# Example: Rendering members in the template

    <?php if (!empty($members)): ?>
    <?php foreach ($members as $member): ?>
    
    <h2><?php echo $member->getName(); ?></h2>
    
    <div class="photo">
      <img src="<?php echo $member->getImageBag()->getUrl('300x300'); ?>" />
    </div>
    
    <article><?php echo $member->getDescription(); ?></article>
    
    <?php endforeach; ?>
    <?php endif; ?>