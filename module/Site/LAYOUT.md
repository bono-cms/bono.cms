
# What is a layout?

A layout is a special file that contains all global HTML blocks, such as the footer, header, and sidebar. For example, if you have an e-commerce theme, you'll notice repetitive blocks on all its pages, which also appear on other pages. Most websites have only one section that changes when navigating between pages, if you look closely. Chances are, your theme is not much different in terms of repetitive blocks. What you need to do is identify which parts of your theme make up the global site layout. In most cases, it looks something like this:

    <!DOCTYPE html>
    <html>
    <head>
      <title>My new theme</title>
      
      <link href="..." rel="stylesheet" />
      <link href="..." rel="stylesheet" />
      <link href="..." rel="stylesheet" />
      <link href="..." rel="stylesheet" />
    </head>
    <body>
      <header>
        <!--Header content usually goes here-->
      </header>
      
      <ul>
        <!--Stuff like breadcrumbs or navigation-->
      </ul>
      
      <div id="content">
         <!-- Dynamic page fragment goes here -->
      </div>
      
      <footer>
        <!-- Footer content goes here -->
      </footer>
      
      <script scr="..."></script>
      <script scr="..."></script>
      <script scr="..."></script>
      <script scr="..."></script>
    </body>
    </html>

As you might have guessed, a dynamic page fragment is content that usually comes from other pages. To make it work, there is a predefined variable called `$fragment`. All you need to do is render it, like this:


      <div id="content">
         <?= $fragment; ?>
      </div>

  
Once you finish building a layout, you must save it as a file inside the theme's directory, and it must be named `__layout__.phtml`.


## Real-World Usage

Bono CMS comes with built-in shared partials for `head` and `script` tags. The aforementioned layout template can be simplified as follows:

      <?php $this->loadPartial('begin'); ?>

      <header>
        <!--Header content usually goes here-->
      </header>
      
      <ul>
        <!--Stuff like breadcrumbs or navigation-->
      </ul>
      
      <div id="content">
         <?= $fragment; ?>
      </div>
      
      <footer>
        <!-- Footer content goes here -->
      </footer>
      
      <?php $this->loadPartial('end'); ?>
      
How does it know which stylesheets and scripts to load? These are defined in the configuration file.
