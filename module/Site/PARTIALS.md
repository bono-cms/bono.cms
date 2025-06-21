Partials
====

Partials are reusable sections of a template that help you avoid duplicating code across your website. They are particularly useful for components such as sidebars, sliders, or any other HTML elements that appear on multiple pages.

## Defining a partial

Partials are stored inside the theme's directory in the `partials` folder. Like theme files, they must have the `.phtml` extension.

## Include defined partial

Once a partial file is created in the partials directory, you can include it using `$this->loadPartial($basename)`.

## Example

Let's create a partial file called `sidebar` and pass a title to it:

    <!-- File location: <theme_dir>/partials/sidebar.phtml -->
    
    <aside>
        <h5 class="mb-3"><?= $title; ?></h5>
    
        <ul class="list-unstyled">
            <li>Some stuff</li>
            <li>Another stuff</li>
        </ul>
    </aside>

Then somewhere in your theme call it like this:

    <?php $this->loadPartial('sidebar', [
        'title' => 'My first partial sidebar'
    ]); ?>

**NOTE:** Variables are optional. If you don't have any variables to pass, you can omit passing an array of variables.

**NOTE:** You can include partials inside other partials in the same way.
