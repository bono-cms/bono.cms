<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
	/**
	 * Returns FAQ manager
	 * 
	 * @return \Faq\Service\FaqManager
	 */
	final protected function getFaqManager()
	{
		return $this->getModuleService('faqManager');
	}
}
