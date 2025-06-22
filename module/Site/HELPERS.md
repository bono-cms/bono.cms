## Overview

The helper methods in the `Element` class simplify rendering commonly used HTML elements in your templates.

## Getting Started

First, include the `Element` class at the top of your PHP template:

    <?php
    
    use Krystal\Form\Element;
    
    ?>

## Rendering Social Media Icons

You can use following methods to render links to social media web-sites:

    <a href="<?= Element::linkFacebook($username); ?>" target="_blank">View Facebook</a>
    <a href="<?= Element::linkInstagram($username); ?>" target="_blank">View Instagram</a>
    <a href="<?= Element::linkTelegram($username); ?>" target="_blank">Message to Telegram</a>
    <a href="<?= Element::linkWhatsApp($username); ?>" target="_blank">Message to WhatsApp</a>

## Rendering Email Links

You can generate a mailto link attribute:

    <a href="<?= Element::createMailTo('email@example.com'); ?>">Email me</a>

Or you can render an email link like this:

    <p><?= Element::linkEmail('me@example.com', 'text-decoration-none'); ?></p>

## Rendering Phone Numbers

To generate a `tel:` attribute only:

    <a href="<?= Element::createTel('+123456789'); ?>">Call me</a>

Or render a complete clickable phone link:

    <?= Element::linkPhone('+123456789', 'text-decoration-none'); ?>

