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
     * Generates details action button
     * 
     * @param string $url
     * @param string $hint
     * @return string
     */
    public static function details($url, $hint)
    {
        return Element::icon('glyphicon glyphicon-fullscreen', $url, array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'data-original-title' => $hint,
            'data-button' => 'details'
        ));
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
        return Element::icon('glyphicon glyphicon-star', $url, array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'data-original-title' => $hint
        ));
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
        return Element::icon('glyphicon glyphicon-pencil', $url, array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'data-original-title' => $hint,
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
        return Element::icon('glyphicon glyphicon-search', $url, array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'data-original-title' => $hint,
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
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'data-original-title' => $hint,
            'data-button' => 'delete',
            'data-url' => $url
        );

        if ($message !== null) {
            $attrs['data-message'] = $message;
        }

        return Element::icon('glyphicon glyphicon-fire', '#', $attrs);
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
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'data-original-title' => $hint,
            'data-button' => 'delete',
            'data-url' => $url
        );

        if ($message !== null) {
            $attrs['data-message'] = $message;
        }

        if ($backUrl !== null) {
            $attrs['data-back-url'] = $backUrl;
        }

        return Element::icon('glyphicon glyphicon-remove', '#', $attrs);
    }
}
