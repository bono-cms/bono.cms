## Overview

The helper methods in the `Element` class simplify rendering commonly used HTML elements in your templates.

## Getting Started

First, include the `Element` class at the top of your PHP template:

    <?php
    
    use Krystal\Form\Element;
    
    ?>

## Rendering Social Media Icons

You can use following methods to render links to social media web-sites:

    Element::linkFacebook($username);
    Element::linkInstagram($username);
    Element::linkTelegram($username);
    Element::linkWhatsApp($username);

## Rendering Email Links

You can generate a mailto link attribute:

    <a href="<?= Element::createMailTo('email@example.com'); ?>">Email me</a>

## Rendering Phone Numbers

To generate a `tel:` attribute only:

    <a href="<?= Element::createTel('+123456789'); ?>">Call me</a>

Or render a complete clickable phone link:

    <?= Element::linkPhone('+123456789'); ?>



