Menu module
===========
This module allows you to easily manage various menus on your site. Most sites have at least two menus - on footer and on header. In order to render menus on your site, you would be using a pre-defined service, which is called `$menu`. In order to use it, you firstly have to configure it in theme's configuration file.

# Configuration
The configuration file is located under current theme's directory - theme.config.php. Open it and create a new section called "menu". Each its key must be a name of category class and a value must be an instance of :

## \Menu\View\BootstrapDropdown

If you want to manage bootstrap-compliant dropdown menu.

## \Menu\View\Dropdown

If you want to manage semantic-compliant regular dropdown menu tree

# Configuration example

Assuming we have a menu category with a class called "top", then the menu section would look as following:

    'menu' => array(
        'top' => new \Menu\View\BootstrapDropdown(),
    ),

# Menu service object

Once we've configured the menu, let's render it. The menu services has the following methods:

## renderByClass(\$class, \$id = null)

Renders menu block by provided category class. If you want current item to be marked as active, then you need to provide an id of that element. In most cases, you can simply pass `$page->getWebPageId()`

# renderByAssocWebPageId(\$id)

Renders a whole category's block by associated web page id. As in example above you should pass a web page id.

## getCategoryNameByClass(\$class)

Returns category name by its associated class
