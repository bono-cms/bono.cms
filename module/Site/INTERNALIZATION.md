# Internationalization (i18n)

Internationalization is the process of making your website to easily support translation into multiple languages.
In Bono CMS, internationalization is built-in and works seamlessly right out of the box. You can effortlessly add as many languages as needed for your website.

## Getting current active locale

`$locale` is a globally available variable in all your templates. It holds the locale code of the currently active language for the current session, and can be used to display the current locale or highlight the active language:

    <li><?= $locale; ?></li>

## Rendering a Language switcher

In your theme template, you can access a built-in array that holds language entities. The language switcher is typically included in the header section of your website.

**NOTE:** If your website supports only one language, the `$languages` array will remain empty.

Each language entity has the following methods:

    $language->getName(); // Returns language name
    $language->getCode(); // Returns language (locale) code
    $language->getOrder(); // Returns sorting order
    $language->getSwitchUrl(); // Returns switch URL for current language
    $language->getDefault(); // Returns TRUE or FALSE, depending if a language has been markerd as default one in administration panel
    $language->getActive(); // Returns whether this language is active on current session

Then  somewhere in `__layout__.phtml__  ` you can render it like this:
    
    <?php if ($languages): ?>
    <ul class="list-unstyled">
        <?php foreach ($languages as $language): ?>
        <li class="<?= $language->getActive() ? 'active' : ''; ?>">
            <a href="<?= $language->getSwitchUrl(); ?>"><?= $language->getName(); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>


## Translating string

You can translate any strings within your theme folder using the `$this->translate()` method.

For example, suppose your default language is English, and you're translating a string into Spanish:

    <section class="pt-5">
        <div class="container">
            <h1><?= $this->translate('Welcome to our web-site'); ?></h1>
        </div>
    </section>

To make this work, ensure the following:

1. You added a language in the administration panel with the locale code `es`.
2. You created a file in `Site/Translations/es/messages.php` that returns an array with the corresponding translations.

       <?php
       /* File: Site/Translations/es/messages.php */
    
       return [
         'Welcome to our web-site' => 'Bienvenido a nuestro sitio web'
       ];
