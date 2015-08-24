FAQ module
==========

This module allows you to manage Frequently-Asked-Questions on your site. In order to start using it, you need to create a new static page, that points to `Faq:Faq@indexAction` controller. Then you must also create a template file for a view, which is called `faq.phtml` in theme folder, you're going to use. Once you do that, you can start iterating over pre-defined array with entities in template file, which is called `$faqs`.

Each faq's entity has the following methods:

# getQuestion()

Returns a question

# getAnswer()

Returns an answer to its corresponding question

# Example: Usage in faq's template

    <?php if (!empty($faqs)) : ?>
    
    <?php foreach($faqs as $faq): ?>
    
      <h2><?php echo $faq->getQuestion(); ?></h2>
      <article><?php echo $faq->getAnswer(); ?></article>
    
    <?php endforeach; ?>
    
    <?php endif; ?>