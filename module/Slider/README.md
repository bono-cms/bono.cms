Slider module
============

This module allows you to manage slides on your site. It has one pre-defined service which is called $slider. The service has two available methods:

# has($class)

Checks whether category has at least one slide image by its associated class name

# getAll($class)

Returns all slide entities by associated category's class name

Each slide's entity has the following methods:
 

# getName()

Returns slide's name

# getDescription()

Returns slide's description

# getLink()

Returns slide's link

# getImageBag()->getUrl($dimension)

Returns URL path to slide's image filtered by provided dimension. Its aware only of its defined in its related category dimensions, otherwise it won't work.


#Example: Rendering a slider on the site

    <?php if ($slider->has('some-category-class-name')): ?>
    
    <ul>
      <?php foreach ($slider->getAll('some-category-class-name') as $slide): ?>
      <li>
          <a href="<?php echo $slide->getLink(); ?>">
            <img src="<?php echo $slide->getImageBag()->getUrl('400x200'); ?>" /><?php echo $slide->getName(); ?>
          </a>
       </li>
      <?php endforeach; ?>
    </ul>
    
    <?php endif; ?>