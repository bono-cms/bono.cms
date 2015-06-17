<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice;

use Advice\Service\AdviceManager;
use Cms\AbstractCmsModule;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		return array(
			'adviceManager' => new AdviceManager($this->getMapper('/Advice/Storage/MySQL/AdviceMapper'), $this->getHistoryManager())
		);
	}
}
