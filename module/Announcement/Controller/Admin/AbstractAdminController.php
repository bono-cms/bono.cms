<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
	/**
	 * Returns announce manager
	 * 
	 * @return \Announcement\Service\AnnounceManager
	 */
	final protected function getAnnounceManager()
	{
		return $this->getModuleService('announceManager');
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Announcement\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getModuleService('categoryManager');
	}
}
