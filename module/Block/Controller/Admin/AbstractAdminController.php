<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
	/**
	 * Returns block manager
	 * 
	 * @return \Block\Service\BlockManager
	 */
	final protected function getBlockManager()
	{
		return $this->getModuleService('blockManager');
	}
}
