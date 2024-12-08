
# Breadcrumbs

Breadcrumbs typically show visitors where they are on your website. If your template includes breadcrumbs, you can use them by accessing the breadcrumb bag instance in your templates.

### Example 1: Usage with built-in Widget 

This approach is recommended if you are using the Bootstrap 5 framework.

    <?php
    
    use Krystal\Widget\Breadcrumbs\BreadcrumbWidget;
    
    ?>
    
    <nav>
        <?= $this->widget(new BreadcrumbWidget()); ?>
    </nav>

If you want to override the default CSS classes, you can pass an array of options.

    <?php
    
    use Krystal\Widget\Breadcrumbs\BreadcrumbWidget;
    
    ?>
    
    <nav>
        <?= $this->widget(new BreadcrumbWidget([
            // Passing array of optional overrides. Listed with ther default values:
            
            // 'ulClass => 'breadcrumb',
            // 'itemClass' => 'breadcrumb-item',
            // 'itemActiveClass' => 'breadcrumb-item active',
            // 'linkClass' => ''
        ])); ?>
    </nav>

**BEST PRACTICE:** Wrap breadcrumbs in their own partial file, `partials/breadcrumbs.phtml`, and use it across the entire website by calling `$this->loadPartial('breadcrumbs')`

## Example 2: Usage without widget

Everything is prepared automatically for you — all you need to do is iterate over the breadcrumb array in your template.

First, you need to ensure that at least one breadcrumb is available, since some pages might not have them. To do this, simply check the value returned by `$this->getBreadcrumbBag()->has()`. This method returns `true` or `false`.
Once you’ve confirmed that breadcrumbs are available, the next step is to iterate over the array. Each key's value represents an instance of the current breadcrumb, with three available methods:

    $breadcrumb->getName(); // Returns a name of current breadcrumb.
    $breadcrumb->getLink(); // Returns a link of current breadcrumb
    $breadcrumb->getName(); // Returns boolean value, which indicates if current breadcrumb is active.

You can simple copy-paste this template fragment and insert it directly in your markup where it's appropriate.

    <?php if ($this->getBreadcrumbBag()->has()): ?>
    <ul class="breadcrumb">
       <?php foreach ($this->getBreadcrumbBag()->getBreadcrumbs() as $breadcrumb): ?>
       
       <?php if ($breadcrumb->isActive()): ?>
       <li class="active"><?= $breadcrumb->getName(); ?></li>
       <?php else: ?>
       <li><a href="<?= $breadcrumb->getLink(); ?>"><?= $breadcrumb->getName(); ?></a></li>
       <?php endif; ?>
       
       <?php endforeach; ?>
    </ul>
    <?php endif; ?>