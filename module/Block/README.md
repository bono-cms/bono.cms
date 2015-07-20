Block module
===========

Sometimes you might have some small HTML block on your site, that you wanted to make editable. This module is specially designed for that. There's a pre-defined service, which is called `$block` and has only one method:

# render($class)

That returns content of a block by its associated class name

# Example

Showing contact information, which is editable from administration panel.

    <footer>
      <?php echo $block->render('footer'); ?>
    </footer>