<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site;

use Cms\AbstractCmsModule;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		// Override parent method by this one returning nothing, since the module doesn't require any configuration data
		return array();
	}
}
