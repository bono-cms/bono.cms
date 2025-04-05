
What is a layout?
=====

A **layout** defines the shared HTML structure of your pages—such as headers, footers, navigation menus, and main content areas.

If you look closely, most websites only update a small section of the page when navigating between views. The rest—the layout—stays the same.

Chances are, your theme follows a similar pattern, with repeated blocks across pages. The key is to identify which parts of your theme make up the **global site layout**. In most cases, it looks something like this:

    <!DOCTYPE html>
    <html>
    <head>
      <title>My new theme</title>
      ...
      
     <?php foreach ($this->getPluginBag()->getStylesheets() as $href): ?>
     <link href="<?= $href; ?>" rel="stylsheet" />
     <?php endforeach; ?>
      
    </head>
    <body>
      <header>
        <!--Header content usually goes here-->
      </header>
      
      <main>
         <!-- Dynamic page fragment goes here -->
      </main>
      
      <footer>
        <!-- Footer goes here -->
      </footer>

      <?php foreach ($this->getPluginBag()->getScripts() as $src): ?>
      <script src="<?= $src; ?>"></script>
      <?php endforeach; ?>
    </body>
    </html>

As you might expect, a **dynamic page fragment** refers to content that's typically pulled in from other pages.

To handle this, there's a predefined variable called `$fragment` that contains the fragment content. Rendering it is simple—just output it like this:

      <main>
         <?= $fragment; ?>
      </main>

There’s a helper called **Plugin Bag** that helps manage your assets. It provides the following methods:

    $this->getPluginBag()->getStylesheets();  // Returns an array of all registered stylesheets
    $this->getPluginBag()->getScripts(); // Returns an array of all registered scripts

Typically, you would call these two methods in your layout file to include the necessary styles and scripts.

**How does it know which stylesheets and scripts to load?**  
It's simple - they’re specified in the configuration file.

Once you've finished building your layout, save it as a file named `__layout__.phtml` inside your theme's directory.

## Real-World Usage

Bono CMS includes built-in shared partials for the `<head>` and `<script>` sections, allowing you to streamline your layout template. With these partials, the layout can be simplified as follows:

      <?php $this->loadPartial('begin'); ?>

      <header>
        <!--Header content usually goes here-->
      </header>
      
      <main>
         <?= $fragment; ?>
      </main>
      
      <footer>
        <!-- Footer content goes here -->
      </footer>
      
      <?php $this->loadPartial('end'); ?>
      