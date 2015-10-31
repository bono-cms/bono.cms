<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Info extends AbstractController
{
    /**
     * Just outputs default PHP info
     * 
     * @return string
     */
    public function indexAction()
    {
        phpinfo();
        exit(1);
    }
}
