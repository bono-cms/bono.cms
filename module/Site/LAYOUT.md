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
      
      <script scr="..."></script>
    </body>
    </html>

As you might expect, a **dynamic page fragment** refers to content that's typically pulled in from other pages.

To handle this, there's a predefined variable called `$fragment` that contains the fragment content. Rendering it is simple—just output it like this:

      <main>
         <?= $fragment; ?>
      </main>

  
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
      
**How does it know which stylesheets and scripts to load?**  
They’re specified in the configuration file.