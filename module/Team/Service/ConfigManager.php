<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Service;

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
		$entity->setPerPageCount((int) $this->get('per_page_count', 20))
			   ->setCoverWidth((float) $this->get('cover_width', 200))
			   ->setCoverHeight((float) $this->get('cover_height', 200));
	}
}
