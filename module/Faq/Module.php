<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq;

use Cms\AbstractCmsModule;
use Faq\Service\FaqManager;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		return array(
			'faqManager' => new FaqManager($this->getMapper('/Faq/Storage/MySQL/FaqMapper'), $this->getHistoryManager())
		);
	}
}
