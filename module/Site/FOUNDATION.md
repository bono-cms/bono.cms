Foundation
====

**In Bono CMS, a theme** is a collection of templates, stylesheets, and assets (like images, fonts, and scripts) that define the visual appearance and layout of your website. It controls how your content is presented to visitors—everything from page structure to colors, typography, and component styling.

While the CMS engine manages your content, the **theme decides how that content looks** on the front end. 

## Setting up the Theme Directory

All theme directories must be placed within the corresponding module, which is `Site`. To do this, navigate to:

`/module/Site/View/Template`

Create a new folder here using the desired name for your theme—for example, `my-new-theme`. Then, copy all your theme files into this newly created directory.

## Configuration

To let the system know which theme to load, mark your theme as the default in the administration panel.

Once that’s done, the system will recognize your theme and load it on startup. However, your theme isn’t fully functional just yet—so let’s move on to the next step.

## Normalizing asset paths


By default, your HTML templates may use local paths for assets. For example:

    <img src="img/logo.png" />

To make these paths work correctly within your theme’s structure, you’ll need to convert them using the `asset()` helper. Simply wrap each path like this:

    <img src="<?= $this->asset('img/logo.png'); ?>" />

This ensures your assets are correctly resolved based on the active theme’s directory.

Make sure to update all asset paths throughout your theme files to use `<?= $this->asset(...) ?>` for consistent and reliable loading.

## Configuration file

The configuration file tells the system what to load for your theme and is always defined as a PHP array located in the theme's folder.

To set this up, create a file named `theme.config.php` inside your theme directory. This file must return a PHP array, typically structured like this:

    <?php
    
    return [
        'meta' => [
            'name' => 'My new custom theme',
            'description' => 'Default blogging theme for Bono CMS',
            'version' => '1.0',
            'author' => 'John Doe'
        ],
       'plugins' => [
          'jquery',
          // Another plugins global to load, if required
       ],
       'theme' => [
          'stylesheets' => [
             '/css/styles.css'
          ],
          'script' => [
             '/js/app.js'
           ]
       ]
    ];

As you can see, the configuration file contains two main keys: `plugins` and `theme`, both returning arrays.

-   The **`plugins`** key lists plugin names, which are defined in `/config/view.php`. Each name corresponds to a set of asset paths for a client-side plugin (e.g., JavaScript libraries).
    
-   The **`theme`** key contains two sub-keys, typically `css` and `js`, which hold arrays of relative paths to your theme’s CSS and JavaScript assets.