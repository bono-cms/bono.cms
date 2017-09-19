<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Social;

abstract class AbstractSocialService
{
    /**
     * Target unique ID for social services
     * 
     * @var string
     */
    protected $url;

    /**
     * State initialization
     * 
     * @param string $url
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Returns share link for social service of the URL
     * 
     * @return array|boolean
     */
    abstract public function getShareLink();

    /**
     * Returns share count for URL
     * 
     * @return array|boolean
     */
    abstract public function getShareCount();
}
