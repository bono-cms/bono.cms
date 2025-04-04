Widgets
=======

Widgets are commonly utilized in the view layer of the MVC pattern. By encapsulating specific UI elements, widgets help break down the interface into smaller, reusable components.

Example: A "Pagination Widget" can be included in multiple templates as a component, reducing repetitive code and ensuring consistency across your application.

In Bono CMS, widgets are easily integrated into templates using the `$this->widget($widget)` method. This method returns the rendered content of the specified widget.

By default, Bono CMS includes two built-in widgets:

- Pagination
- Breadcrumbs

In the following chapters, you'll see how a single line of code can greatly simplify the rendering of common components by using widgets.