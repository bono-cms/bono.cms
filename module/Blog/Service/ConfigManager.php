<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

use Krystal\Config\File\AbstractConfigManager;
use Krystal\Security\Filter;

final class ConfigManager extends AbstractConfigManager
{
	/**
	 * {@inheritDoc}
	 */
	protected function populate()
	{
		$entity = $this->getEntity();
		$entity->setPerPageCount(Filter::escape($this->get('per_page_count'), 10));
	}
}
