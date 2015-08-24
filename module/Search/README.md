Search module
=============


This module allows you to attach searching functionality on your site. Modules which support searching functionality are attached automatically, so you don't need to do anything. Before you start using it, you have to create a view template, which is called `search.phtml` under your current theme directory. The module has one service, which is called `$search` with following methods:

## getUrl()

Returns URL where all search request must be send to

## getElementName()

Returns a name of an element query's text should come from

## getKeyword()

Returns current keyword typed by user


## Template variables

There are 2 pre-defined variables for search template:

# $paginator

Since search results can be paginated, `$paginator` service contains relevant information.

# $errors

An array of error messages
 

# Example: Typical fragment in layout

    <form action="<?php echo $search->getUrl(); ?>">
       <input type="text" name="<?php echo $search->getElementName(); ?>" value="<?php echo $search->getKeyword(); ?>" />
       <button type="submit">Search!</button>
    </form>

 

#Example: Building the structure for search's template

Note, this example assumes, that you have already tweaked pagination block;

    <div class="container">
    
        <?php if (empty($errors)): ?>
    
        <div class="page-header">
            <h3>Search results for "<?php echo $search->getKeyword(); ?>" (<?php echo $paginator->getTotalAmount(); ?>)</h3>
        </div>
    
        <?php if (!empty($results)): ?>
    
        <div class="row">
            <ul>
                <?php foreach ($results as $result): ?>
                <li>
                    <h3><a href="<?php echo $result->getUrl(); ?>"><?php echo $result->getTitle(); ?></a></h3>
                    <article><?php echo $result->getContent(); ?></article>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    
        <?php $this->loadBlock('paginate'); ?>
    
        <?php else: ?>
        
        <div class="block-center">
            <h2><?php $this->show('No results'); ?></h2>
        </div>
    
        <?php endif; ?>
    
        <?php else: ?>
    
        <div class="page-header">
            <?php foreach($errors as $error): ?>
            <h3><?php echo $error; ?></h3>
            <?php endforeach; ?>
        </div>
    
        <div class="row"></div>
    
        <?php endif; ?>
    </div>

