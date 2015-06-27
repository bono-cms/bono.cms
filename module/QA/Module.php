<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace QA;

use Cms\AbstractCmsModule;
Use QA\Service\QaManager;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		return array(
			'qaManager' => new QaManager($this->getMapper('/QA/Storage/MySQL/QaMapper'), $this->getHistoryManager())
		);
	}
}
