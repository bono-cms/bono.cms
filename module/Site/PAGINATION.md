
Pagination
======

Pagination simplifies dividing large datasets into manageable chunks and provides a user-friendly interface to navigate through them.

You might use pagination in modules such as Blog, News, Shop, Structure, etc.

We highly recommend creating a partial template file first, as pagination can be shared across different modules, and the same syntax is used repeatedly.

So, go ahead and create a template file in your theme's directory: `partials/pagination.phtml`

## Usage with Widget

Add the following snippet in your `partials/pagination.phtml` template file:

    <?php
    
    use Krystal\Widget\Pagination\PaginationWidget;
    
    ?>
    
    <?php if (isset($paginator)): ?>
    <?= $this->widget(new PaginationWidget($paginator)); ?>
    <?php endif; ?>

And then, use it like this in your template where appropriate:

    <nav>
        <?php $this->loadPartial('pagination'); ?>
    </nav>

## Usage without Widget

In some rare cases, you might want to output pagination manually. A manual render looks like this:

    <?php if (isset($paginator) && $paginator->hasPages()): ?>
      <ul class="pagination">
    	<?php if ($paginator->hasPreviousPage()): ?>
    	<li>
    	  <a href="<?= $paginator->getPreviousPageUrl(); ?>" aria-label="Previous">
    		<span aria-hidden="true">&laquo;</span>
    	  </a>
    	</li>
    	<?php endif; ?>
    
    	<?php foreach ($paginator->getPageNumbers() as $page): ?>
    	<?php if (is_numeric($page)): ?>
    	<?php if ($paginator->isCurrentPage($page)): ?>
    	
    	<li class="active"><a href="<?= $paginator->getUrl($page); ?>"><?= $page; ?></a></li>
    	<?php else: ?>
    	
    	<li><a href="<?= $paginator->getUrl($page); ?>"><?= $page; ?></a></li>
    	<?php endif; ?>
    	
    	<?php else: ?>
    	<li><a href="#"><?= $page; ?></a></li>
    	<?php endif; ?>
    	<?php endforeach; ?>
    	
    	<?php if ($paginator->hasNextPage()): ?>
    	<li>
    	  <a href="<?= $paginator->getNextPageUrl(); ?>" aria-label="Next">
    		<span aria-hidden="true">&raquo;</span>
    	  </a>
    	</li>
    	<?php endif; ?>
      </ul>