
# Breadcrumbs

Breadcrumbs help visitors understand where they are on your website. If your template includes breadcrumb navigation, you can use it by accessing the breadcrumb bag instance in your templates.

### Example 1: Using the Built-in Widget

This method is recommended if you're using the Bootstrap 5 framework.

    <?php
    
    use Krystal\Widget\Breadcrumbs\BreadcrumbWidget;
    
    ?>
    
    <nav>
        <?= $this->widget(new BreadcrumbWidget()); ?>
    </nav>

Want to customize the default CSS classes? Just pass an array of options like this:

    <?php
    
    use Krystal\Widget\Breadcrumbs\BreadcrumbWidget;
    
    ?>
    
    <nav>
        <?= $this->widget(new BreadcrumbWidget([
            // Passing array of optional overrides. Listed with ther default values:
            'ulClass => 'breadcrumb',
            'itemClass' => 'breadcrumb-item',
            'itemActiveClass' => 'breadcrumb-item active',
            'linkClass' => 'text-decoration-none'
        ])); ?>
    </nav>

**BEST PRACTICE:** For consistency, place breadcrumbs in a separate partial file, like `partials/breadcrumbs.phtml`, and include it site-wide using `$this->loadPartial('breadcrumbs');` 

## Example 2: Using Breadcrumbs Without the Widget

Bono automatically prepares everything for you. All you need to do is loop through the breadcrumb array in your template.
First, check if breadcrumbs exist since some pages might not have them. The method `$this->getBreadcrumbBag()->has()` returns `true` or `false`.
Once breadcrumbs are confirmed, loop through the array. Each breadcrumb object provides three methods:

    $breadcrumb->getName(); // Returns the breadcrumb name
    $breadcrumb->getLink(); // Returns the breadcrumb URL
    $breadcrumb->getName(); // Returns true if it's the current page

Here’s a simple code snippet to drop right into your markup:

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

This is an easy way to make your website's navigation more intuitive for users!