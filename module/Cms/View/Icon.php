<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\View;

use Krystal\Form\Element;

final class Icon
{
    /**
     * Generates regular button
     * 
     * @param string $icon
     * @param string $url
     * @param string $hint
     * @param array $extras Extra attributes
     * @return string
     */
    public static function button($icon, $url, $hint, $extras = array())
    {
        $attributes = array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'data-original-title' => $hint,
        );

        // Append extras, if any
        $attributes = array_replace($attributes, $extras);

        return Element::icon($icon, $url, $attributes);
    }

    /**
     * Renders approve action button
     * 
     * @param string $url
     * @param string $hint
     * @return string
     */
    public static function approve($url, $hint)
    {
        return self::button('far fa-check-square', $url, $hint, array(
            'data-button' => 'approve'
        ));
    }

    /**
     * Generates details action button
     * 
     * @param string $url
     * @param string $hint
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function details($url, $hint, array $attributes = array())
    {
        $attributes = array_merge(array(
            'data-button' => 'details',
            'data-url' => $url
        ), $attributes);

        return self::button('fas fa-receipt', $url, $hint, $attributes);
    }

    /**
     * Generates polls action button
     * 
     * @param string $url
     * @param string $hint
     * @return string
     */
    public static function polls($url, $hint)
    {
        return self::button('far fa-chart-bar', $url, $hint);
    }

    /**
     * Generates edit action button
     * 
     * @param string $url
     * @param string $hint
     * @return string
     */
    public static function edit($url, $hint)
    {
        return self::button('fas fa-edit', $url, $hint, array(
            'data-button' => 'edit'
        ));
    }

    /**
     * Generates view action button
     * 
     * @param string $url
     * @param string $hint
     * @return string
     */
    public static function view($url, $hint)
    {
        return self::button('fas fa-search', $url, $hint, array(
            'data-button' => 'view',
            'target' => '_blank'
        ));
    }

    /**
     * Generates reset action button
     * 
     * @param string $url
     * @param string $hint
     * @param string $message Optional message to be overriden
     * @return string
     */
    public static function reset($url, $hint, $message = null)
    {
        $attrs = array(
            'data-button' => 'delete',
            'data-url' => $url
        );

        if ($message !== null) {
            $attrs['data-message'] = $message;
        }

        return self::button('fas fa-fire', $url, $hint, $attrs);
    }

    /**
     * Generates removal action button
     * 
     * @param string $url
     * @param string $hint
     * @param string $message Optional message to be overriden
     * @param string $backUrl Optional URL to be redirected on success
     * @return string
     */
    public static function remove($url, $hint, $message = null, $backUrl = null)
    {
        $attrs = array(
            'data-button' => 'delete',
            'data-url' => $url
        );

        if ($message !== null) {
            $attrs['data-message'] = $message;
        }

        if ($backUrl !== null) {
            $attrs['data-back-url'] = $backUrl;
        }

        return self::button('fas fa-ban', $url, $hint, $attrs);
    }
}
