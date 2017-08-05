<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Service;

use Cms\Service\WebPageManagerInterface;

final class SiteService implements SiteServiceInterface
{
    /**
     * Web page manager service
     * 
     * @var \Cms\Service\WebPageManagerInterface
     */
    private $webPageManager;

    /**
     * State initialization
     * 
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @return void
     */
    public function __construct(WebPageManagerInterface $webPageManager)
    {
        $this->webPageManager = $webPageManager;
    }

    /**
     * Creates URL by target ID and module name
     * 
     * @param string $targetId
     * @param string $module
     * @return string
     */
    public function createUrl($targetId, $module)
    {
        return $this->webPageManager->createUrl($targetId, $module);
    }
}
