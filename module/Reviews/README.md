Reviews module
==============

This module allows you to manage feedback from your visitors on your site. In order to start using it, you need to create a static page and lead its controller to `Reviews:Reviews@showAction`. Then inside a template you'd have two variables `$reviews` and `$paginator`. First variable is an array of entities, and the second is an instance of paginator. Each review's entity has the following methods:

# getTimestamp()

Returns UNIX timestamp of creation

# getName()

Returns a name of reviewer

# getEmail()

Returns an email of reviewer

# getContent()

Returns a message of reviewer

# Example: Rendering reviews

    <?php if (!empty($reviews)): ?>
    <?php foreach ($reviews as $review): ?>
    
    <h2><?php echo $review->getName(); ?> / <?php echo $review->getEmail(); ?></h2>
    <article><?php echo $review->getContent(); ?>
    
    <?php endforeach; ?>
    <?php endif; ?>