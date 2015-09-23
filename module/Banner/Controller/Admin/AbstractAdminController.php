<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Banner\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
	/**
	 * Just returns banner manager
	 * 
	 * @return \Banner\Service\BannerManager
	 */
	final protected function getBannerManager()
	{
		return $this->getModuleService('bannerManager');
	}
}
